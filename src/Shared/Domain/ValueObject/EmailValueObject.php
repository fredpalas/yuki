<?php

namespace App\Shared\Domain\ValueObject;

class EmailValueObject extends StringValueObject implements ValueObject
{
    public function __construct(protected string $value)
    {
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }

        parent::__construct($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
