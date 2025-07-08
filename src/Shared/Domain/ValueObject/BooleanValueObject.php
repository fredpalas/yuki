<?php

namespace App\Shared\Domain\ValueObject;

abstract class BooleanValueObject implements ValueObject
{
    public function __construct(protected bool $value)
    {
    }

    public function value(): bool
    {
        return $this->value;
    }

    /**
     * @param bool $value
     * @return static
     */
    public static function fromBoolean(bool $value): BooleanValueObject
    {
        return new static($value);
    }

    public function isTrue(): bool
    {
        return true === $this->value;
    }

    public function isFalse(): bool
    {
        return false === $this->value;
    }

    public function negate(): self
    {
        return new static(!$this->value);
    }

    public static function true(): static
    {
        return new static(true);
    }

    public static function false(): static
    {
        return new static(false);
    }

    public function equals(BooleanValueObject $other): bool
    {
        return $other->value() === $this->value();
    }
}
