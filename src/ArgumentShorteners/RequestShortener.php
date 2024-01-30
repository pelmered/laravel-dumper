<?php

namespace Pelmered\LaravelDumper\ArgumentShorteners;

use Pelmered\LaravelDumper\DataTypes\ShortendArgument;
use Symfony\Component\HttpFoundation\Request;

class RequestShortener extends ArgumentShortener
{
    public function shouldRun(): bool
    {
        return $this->argument instanceof Request;
    }

    public function shorten(): ShortendArgument
    {
        return new ShortendArgument(
            name: $this->name,
            value: [
                'uri' => $this->argument->getUri(),
                'method' => $this->argument->getMethod(),
                'headers' => $this->argument->headers->all(),
                'body' => $this->argument->toArray(),
            ],
            originalValue: $this->argument,
            type: get_class($this->argument),
            shortener: 'request',
        );
    }
}
