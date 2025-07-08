<?php

namespace App\Contexts\Blog\Author\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

class AuthorCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $name,
        public readonly string $surname,
        ?string $eventId = null,
        ?string $occurredOn = null
    )
    {
        parent::__construct(
            $id,
            $eventId,
            $occurredOn
        );
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $body['name'],
            $body['surname'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'blog.author.created';
    }

    public function toPrimitives(): array
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
        ];
    }
}
