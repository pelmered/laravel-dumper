<?php

namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Illuminate\Support\Collection;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;

class CollectionShortener extends ArgumentShortener
{
    public function shouldRun(): bool
    {
        return $this->argument instanceof Collection;
    }

    public function shorten(): ShortendArgument
    {
        return new ShortendArgument(
            name: $this->name,
            value: $this->argument->toArray(),
            originalValue: $this->argument,
            type: get_class($this->argument),
            shortener: 'Collection',
        );
    }
}
