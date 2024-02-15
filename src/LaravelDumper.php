<?php

namespace Pelmered\LaravelDumper;

use Illuminate\Support\Arr;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;
use Pelmered\LaravelDumper\DataTypes\TraceFrame;
use Pelmered\LaravelDumper\ErrorReporters\ErrorReporter;
use Pelmered\LaravelDumper\ErrorReporters\Flare;
use Pelmered\LaravelDumper\ErrorReporters\Local;
use Pelmered\LaravelDumper\ErrorReporters\Sentry;
use PhpParser\Node\Scalar;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class LaravelDumper
{
    public static function dump($var)
    {
        $dumper = new \Symfony\Component\VarDumper\Dumper\HtmlDumper();
        $dumper->dump((new \Symfony\Component\VarDumper\Cloner\VarCloner())->cloneVar($var));
    }

    public static function getCaller($withArguments = true, $withCodeSnippet = false, $only = null)
    {
        $callerStack = static::trace(3, $withArguments, $withCodeSnippet, $only);

        return $callerStack[2];
    }

    public static function getErrorReporter(): ErrorReporter
    {
        $errorReporter = config('dumper.error_reporter');

        return match ($errorReporter) {
            'flare' => new Flare(),
            'sentry' => new Sentry(),
            default => is_string($errorReporter) ? new $errorReporter() : Local::class,
        };
    }

    public static function generateExceptionID(): string
    {
        return (static::getErrorReporter())::generateExceptionID();
    }

    /**
     * Convert the given exception to an array.
     */
    public static function exceptionToArray(Throwable $exception, bool $debug = false): array
    {
        $exceptionID = static::generateExceptionID();

        return $debug || config('app.debug') ? [
            'message' => $exception->getMessage(),
            'error_id' => $exceptionID,
            'error_code' => method_exists($exception, 'getErrorCode') ? $exception->getErrorCode() : null,
            'debug_data' => method_exists($exception, 'getDebugData') ? print_r($exception->getDebugData(), true) : null,
            'severity' => static::getExceptionSeverityString($exception),
            'exception' => $exception::class,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : [
            'message' => static::isHttpException($exception) ? $exception->getMessage() : 'Server Error',
            'error_id' => $exceptionID,
        ];
    }

    /**
     * Determine if the given exception is an HTTP exception.
     */
    public static function isHttpException(Throwable $e): bool
    {
        return $e instanceof HttpExceptionInterface;
    }

    public static function getExceptionSeverityString(Throwable $e): string
    {
        if (! method_exists($e, 'getSeverity')) {
            return '';
        }

        $severity = $e->getSeverity();

        switch ($severity) {
            case E_DEPRECATED:
                return 'DEPRECATED';
            case E_USER_DEPRECATED:
                return 'USER_DEPRECATED';
            case E_WARNING:
                return 'WARNING';
            case E_USER_WARNING:
                return 'USER_WARNING';
            case E_RECOVERABLE_ERROR:
                return 'RECOVERABLE_ERROR';
            case E_ERROR:
                return 'ERROR';
            case E_PARSE:
                return 'PARSE';
            case E_CORE_ERROR:
                return 'CORE_ERROR';
            case E_CORE_WARNING:
                return 'CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'COMPILE_WARNING';
            case E_USER_ERROR:
                return 'USER_ERROR';
            case E_NOTICE:
                return 'NOTICE';
            case E_USER_NOTICE:
                return 'USER_NOTICE';
            case E_STRICT:
                return 'STRICT';
            default:
                return 'UNKNOWN';
        }
    }

    /**
     * Retrieve the context of callable for debugging purposes.
     */
    public static function getCallableContext(callable $callable): array
    {
        switch (true) {
            case \is_string($callable) && \strpos($callable, '::'):
                return ['static method' => $callable];
            case \is_string($callable):
                return ['function' => $callable];
            case \is_array($callable) && \is_object($callable[0]):
                return ['class' => \get_class($callable[0]), 'method' => $callable[1]];
            case \is_array($callable):
                return ['class' => $callable[0], 'static method' => $callable[1]];
            case $callable instanceof \Closure:
                try {
                    $reflectedFunction = new \ReflectionFunction($callable);
                    $closureClass = $reflectedFunction->getClosureScopeClass();
                    $closureThis = $reflectedFunction->getClosureThis();
                } catch (\ReflectionException $e) {
                    return ['closure' => 'closure'];
                }

                return [
                    'closure this' => $closureThis ? $closureThis::class : $reflectedFunction->name,
                    'closure scope' => $closureClass ? $closureClass->getName() : $reflectedFunction->name,
                    'static variables' => static::formatVariablesArray($reflectedFunction->getStaticVariables()),
                ];
            case \is_object($callable):
                return ['invokable' => $callable::class];
            default:
                return ['unknown' => 'unknown'];
        }
    }

    /**
     * Format variables array for debugging purposes in order to avoid dumping of huge objects
     */
    public static function formatVariablesArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (\is_object($value)) {
                $data[$key] = $value::class;
            } elseif (\is_array($value)) {
                $data[$key] = static::formatVariablesArray($value);
            }
        }

        return $data;
    }

    public static function trace(?int $limit = 20, $withArguments = true, $withCodeSnippet = false, $only = null): array
    {
        $backtrace = Backtrace::create();

        if ($withArguments) {
            $backtrace->withArguments();
        }

        $backtrace->limit($limit + 1); // We are stripping the first frame, so we need to add one to the limit

        $frames = $backtrace->frames();

        // Shift out the first frame, which is this current 'trace' method
        array_shift($frames);

        return array_map(static function ($frame) use ($withCodeSnippet, $only) {
            $f = new TraceFrame(
                file: $frame->file,
                line: $frame->lineNumber,
                class: $frame->class,
                method: $frame->method,
                arguments: static::getFrameArguments($frame),
            );

            if ($withCodeSnippet) {
                $f->snippet = $frame->getSnippetAsString(10);
            }

            return $f;

            return $only ? Arr::only($f, $only) : $f;
        }, $frames);
    }

    private static function getFrameArguments(Frame $frame): array
    {
        return Arr::map($frame->arguments, static fn ($argument) => static::shortenArgument($argument));
    }

    public static function shortenArgument($argument, $maxDepth = 3, $currentDepth = null, ?string $name = null)
    {
        $currentDepth = $currentDepth ? $currentDepth + 1 : 1;

        if ($maxDepth < $currentDepth) {
            return static::isSimpleValue($argument) ? $argument : '['.static::getCount($argument).']';
            /*
            return '['.static::getCount($argument).']';
            $typeString = static::getTypeString($argument, false);
            return $typeString === '' ? $argument : $typeString . '(' . static::getCount($argument).')';
            */
        }

        /** @var ShortendArgument $value */
        $value = static::runShortenerChain($argument, $name);

        if ($value->isScalar()) {
            return $value->toDisplay();
        }

        if (count($value->value) === 0) {
            return [];
        }

        if (is_array($value->value) && count($value->value) === 1) {
            //dump('is_scalar', is_scalar($value->value[0]));
            $current = current($value->value);
            if (is_scalar($current)) {
                self::returnScalarValue($current);
            }
        }

        $return = [];
        foreach ($value->value as $key => $item) {
            if (is_scalar($item)) {
                $return[] = self::returnScalarValue($item, $key);
                //dump(self::returnScalarValue($item, $key));
            }
        }

        if (count($return) >= 1) {
            return $return;
        }

        //dump($value->value, $maxDepth, $currentDepth);
        return Arr::mapWithKeys(
            $value->value,
            fn ($item, $name) => static::shortenArgument($item, $maxDepth, $currentDepth, $name)
        );
        //return Arr::map($value, fn ($item) => static::shortenArgument($item, $maxDepth, $currentDepth));
    }

    public static function returnScalarValue($value, $key = null)
    {
        //$key = key($value);
        if (is_int($key)) {
            return $value;
        }

        return [$key => $value];
    }

    private static function runShortenerChain($argument, ?string $name = null): Scalar|ShortendArgument
    {
        $shorteners = config('dumper.shorteners');

        //$name =>
        foreach ($shorteners as $shortener) {
            $shortener = new $shortener($argument, $name);
            if ($shortener->shouldRun()) {
                return $shortener->shorten();
            }
        }

        return $argument;
    }

    public static function getTypeString($argument, $withFormatting = true): string
    {
        if (! config('dumper.show_type_info')) {
            return '';
        }

        $typeString = match (true) {
            is_object($argument) => get_class($argument),
            is_array($argument) => 'array',
            is_scalar($argument), is_null($argument) => null,
            default => gettype($argument),
        };

        if ($typeString) {
            return $withFormatting ? ' ('.$typeString.')' : $typeString;
        }

        return '';
    }

    public static function isSimpleValue($argument): bool
    {
        return ! is_array($argument) && ! is_object($argument);
    }

    public static function getCount($argument): ?int
    {
        if (is_object($argument)) {
            $argument = static::shortenArgument($argument);
        }

        return count($argument);
    }
}
