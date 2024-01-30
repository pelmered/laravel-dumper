<?php
namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Illuminate\Support\Arr;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;
use Pelmered\LaravelDumper\LaravelDumper;

class ObjectShortener extends ArgumentShortener
{

    public function shouldRun(): bool
    {
        return is_object($this->argument);
    }

    public function shorten(): ShortendArgument
    {
        return new ShortendArgument(
            name: $this->name,
            value: static::getObjectProperties($this->argument),
            originalValue: $this->argument,
            type: get_class($this->argument),
            shortener: 'object',
        );
    }

    public static function getObjectProperties(object $object, $filter = null): array
    {
        $reflection = new \ReflectionClass($object);
        $props      = $reflection->getProperties();

        return Arr::mapWithKeys($props, static function ($prop, $key) use ($object) {
            $value = $prop->getValue($object);
            return [$prop->name. LaravelDumper::getTypeString($value) => LaravelDumper::shortenArgument($value)];
        });
    }
}
