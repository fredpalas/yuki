<?php

namespace App\Shared\Domain\ValueObject;

abstract class ArrayValueObject implements ValueObject
{
    protected array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function value(): array
    {
        return $this->value;
    }

    public function includes($value): bool
    {
        return in_array($value, $this->value);
    }

    public function includesByKey($key, $value): bool
    {
        return array_key_exists($key, $this->value) && $this->value[$key] === $value;
    }

    public function get($key, $default = null)
    {
        return $this->value[$key] ?? $default;
    }

    public static function create(?array $templateVariables): static
    {
        return new static($templateVariables);
    }
}
