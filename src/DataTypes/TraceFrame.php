<?php

namespace Pelmered\LaravelDumper\DataTypes;

class TraceFrame
{
    public function __construct(
        public string $file,
        public int $line,
        public string $class,
        public string $method,
        public array $arguments = [],
        public ?string $snippet = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'file' => $this->file,
            'line' => $this->line,
            'class' => $this->class,
            'method' => $this->method,
            'arguments' => $this->arguments,
            'snippet' => $this->snippet,
        ];
    }
}
