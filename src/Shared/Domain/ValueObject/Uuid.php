<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid implements \Stringable, ValueObject
{
    public function __construct(protected string $value)
    {
        $this->ensureIsValidUuid($value);
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public static function random(): self
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new \InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }

    public static function create(string $uuid): static
    {
        return new static($uuid);
    }

    public function toBinary(): string
    {
        return hex2bin(str_replace('-', '', $this->value));
    }

    public static function fromBinary(string $binary): static
    {
        return new static(bin2hex($binary));
    }

    public function toRfc4122(): string
    {
        return $this->value;
    }
}
