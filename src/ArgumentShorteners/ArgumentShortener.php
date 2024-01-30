<?php

namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Pelmered\LaravelDumper\DataTypes\ShortendArgument;

abstract class ArgumentShortener
{
    public function __construct(public mixed $argument, public ?string $name)
    {
    }

    abstract public function shouldRun(): bool;

    abstract public function shorten(): ShortendArgument;

    public function getName(): string
    {
        return $this->name ?? gettype($this->argument);
    }
}
