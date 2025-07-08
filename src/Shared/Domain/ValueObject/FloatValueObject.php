<?php

namespace App\Shared\Domain\ValueObject;

abstract class FloatValueObject implements ValueObject
{
    public function __construct(protected float $value)
    {
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(ValueObject $object): bool
    {
        return $this->value() === $object->value();
    }
}
