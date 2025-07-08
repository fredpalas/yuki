<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class StringValueObject implements ValueObject, \Stringable
{
    public function __construct(protected string $value)
    {
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function create(string $value): static
    {
        return new static($value);
    }

    public function equals(StringValueObject $valueObject): bool
    {
        return $this->value() === $valueObject->value();
    }

    public function isEqualString(string $value): bool
    {
        return $this->value() === $value;
    }
}
