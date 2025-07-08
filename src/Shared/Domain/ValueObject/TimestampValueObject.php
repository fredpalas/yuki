<?php

namespace App\Shared\Domain\ValueObject;

use DateTimeInterface;

abstract class TimestampValueObject implements \Stringable, ValueObject
{
    public function __construct(protected \DateTimeInterface $value)
    {
    }

    public function value(): \DateTimeInterface
    {
        return $this->value;
    }

    public static function now(): static
    {
        return new static(new \DateTime());
    }

    public static function fromString(string $datetime): static
    {
        return new static(new \DateTime($datetime));
    }

    public function equals(TimestampValueObject $other): bool
    {
        return $this->value->format('c') === $other->value->format('c');
    }

    public function __toString(): string
    {
        return $this->value->format('c');
    }

    public function isAfter(DateTimeInterface $other): bool
    {
        return $this->value > $other;
    }

    public function isBefore(DateTimeInterface $other): bool
    {
        return $this->value < $other;
    }

    public function isSameOrAfter(DateTimeInterface $other): bool
    {
        return $this->value >= $other;
    }

    public function isSameOrBefore(DateTimeInterface $other): bool
    {
        return $this->value <= $other;
    }

    public function toIso8601(): string
    {
        return $this->value->format(DATE_ATOM);
    }
}
