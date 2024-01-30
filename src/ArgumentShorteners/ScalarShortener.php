<?php
namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Pelmered\LaravelDumper\DataTypes\ShortendArgument;

class ScalarShortener extends ArgumentShortener
{
    public function shouldRun(): bool
    {
        return is_scalar($this->argument) || is_null($this->argument);
    }

    public function shorten(): ShortendArgument
    {
        return new ShortendArgument(
            name: $this->name,
            value: $this->argument,
            originalValue: $this->argument,
            type: gettype($this->argument),
            shortener: 'scalar',
        );
    }
}
