<?php
namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Illuminate\Contracts\Support\Arrayable;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;

class ArrayShortener extends ArgumentShortener
{
    public function shouldRun(): bool
    {
        return (is_array($this->argument) || $this->argument instanceof Arrayable);
    }

    public function shorten(): ShortendArgument
    {
        $array = match (true) {
            $this->argument instanceof Arrayable => $this->argument->toArray(),
            is_array($this->argument) => $this->argument,
            default => [],
        };

        return new ShortendArgument(
            name: $this->name,
            value: $array,
            originalValue: $this->argument,
            type: 'array',
            shortener: 'array',
        );
    }
}

