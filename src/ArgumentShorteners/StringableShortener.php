<?php
namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Illuminate\Support\Stringable;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;

class StringableShortener extends ArgumentShortener
{
    public function shouldRun(): bool
    {
        return (
            is_object($this->argument)
            && (
                 $this->argument instanceof Stringable
                 || method_exists($this->argument, 'toString')
                 || method_exists($this->argument, '__toString')
            )
        );
    }

    public function shorten(): ShortendArgument
    {
        return new ShortendArgument(
            name: $this->name,
            value: $this->getArgumentValue(),
            originalValue: $this->argument,
            type: is_string($this->argument) ? 'string' : get_class($this->argument),
            shortener: 'stringable',
        );
    }

    protected function getArgumentValue(): string
    {
        try {
            return $this->argument->toString();
        } catch (\Throwable $th) {
            return $this->argument->__toString();
        }
    }
}
