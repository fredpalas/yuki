<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Assert;

abstract class ObjectValueObject implements ValueObject
{
    public function __construct(protected $value)
    {
        Assert::instanceOf($this->type(), $value);
    }

    abstract public static function type(): string;

    public function value(): object
    {
        return $this->value;
    }

    public function equals(ValueObject $object): bool
    {
        return $this->value() === $object->value();
    }
}
