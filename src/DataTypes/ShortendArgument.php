<?php
namespace Pelmered\LaravelDumper\DataTypes;

use Pelmered\LaravelDumper\LaravelDumper;

class ShortendArgument
{
    public function __construct(
        public ?string $name,
        public null|string|array $value,
        public mixed $originalValue,
        public ?string $type = null,
        public ?string $shortener = null,
    )
    {
        if (!$type) {
            $this->type = LaravelDumper::getTypeString($originalValue, false);
        }
    }

    public function toDisplay(): array
    {
        return [$this->getKey() => $this->value];
    }

    protected function getKey()
    {
        if (strtolower($this->name) === $this->type) {
            return $this->name;
        }

        return $this->name . (config('dumper.show_type_info') ? ' (' . $this->type . ')' : '');
    }

    public function isScalar()
    {
        return is_scalar($this->originalValue) || is_null($this->originalValue);
    }
}
