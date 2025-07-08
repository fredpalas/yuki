<?php

namespace App\Contexts\Blog\Post\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

class PostCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $aggregateId,
        public readonly string $authorId,
        public readonly string $title,
        public readonly string $description,
        public readonly string $content,
        public readonly string $authorName,
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
            $aggregateId,
            $body['authorId'],
            $body['title'],
            $body['description'],
            $body['content'],
            $body['authorName'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'blog.post.created';
    }

    public function toPrimitives(): array
    {
        return [
            'authorId' => $this->authorId,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'authorName' => $this->authorName,
        ];
    }

}
