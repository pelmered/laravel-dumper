<?php

namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Carbon\Carbon;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;

class CarbonShortener extends ArgumentShortener
{
    public function shouldRun(): bool
    {
        return $this->argument instanceof Carbon;
    }

    public function shorten(): ShortendArgument
    {
        return new ShortendArgument(
            name: $this->name,
            value: $this->argument->toDateTimeString(),
            originalValue: $this->argument,
            type: 'Carbon',
            shortener: 'Carbon',
        );
    }
}
