<?php

namespace App\Contexts\Blog\Author\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

class AuthorNameUpdatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        public readonly string $name,
        public readonly string $previousName,
        ?string $eventId = null,
        ?string $occurredOn = null
    ) {
        parent::__construct(
            aggregateId: $aggregateId,
            eventId: $eventId,
            occurredOn: $occurredOn
        );
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            aggregateId: $aggregateId,
            name: $body['name'],
            previousName: $body['previous_name']
        );
    }

    public static function eventName(): string
    {
        return 'blog.author.name.updated';
    }

    public function toPrimitives(): array
    {
        return [
            'name' => $this->name,
            'previous_name' => $this->previousName,
        ];
    }
}
