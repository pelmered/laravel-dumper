<?php

namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Illuminate\Database\Eloquent\Model;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;

class ModelShortener extends ArgumentShortener
{
    public function shouldRun(): bool
    {
        return $this->argument instanceof Model;
    }

    public function shorten(): ShortendArgument
    {
        return new ShortendArgument(
            name: $this->getName(),
            value: $this->argument->toArray(),
            originalValue: $this->argument,
            type: get_class($this->argument),
            shortener: 'model',
        );
    }
}
