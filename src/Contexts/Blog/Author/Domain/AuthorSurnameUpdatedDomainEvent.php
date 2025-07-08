<?php

namespace App\Contexts\Blog\Author\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

class AuthorSurnameUpdatedDomainEvent extends DomainEvent
{

    public function __construct(
        string $aggregateId,
        public readonly ?string $surname = null,
        public readonly ?string $oldSurname = null,
        ?string $eventId = null,
        ?string $occurredOn = null
    )
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            aggregateId: $aggregateId,
            surname: $body['surname'] ?? null,
            oldSurname: $body['old_surname'] ?? null,
            eventId: $eventId,
            occurredOn: $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'author.surname.updated';
    }

    public function toPrimitives(): array
    {
        return [
            'surname' => $this->surname,
            'old_surname' => $this->oldSurname,
        ];
    }

}
