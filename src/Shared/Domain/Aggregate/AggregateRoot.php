<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Collection;
use App\Shared\Domain\ValueObject\ValueObject;
use ReflectionClass;
use ReflectionProperty;

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\reduce;

abstract class AggregateRoot
{
    use Trait\ToPrimitiveTrait;

    private array $domainEvents = [];

    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    protected function domainEventExist(string $domainEventClass): bool
    {
        return reduce(
            fn (bool $carry, DomainEvent $domainEvent) => $carry || $domainEvent::eventName() === $domainEventClass,
            $this->domainEvents,
            false
        );
    }
}
