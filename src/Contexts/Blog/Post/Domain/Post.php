<?php

namespace App\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Post extends AggregateRoot
{
    public function __construct(
        public private(set) PostId $id,
        public private(set) AuthorId $authorId,
        public private(set) PostTitle $title,
        public private(set) PostContent $content,
        public private(set) PostDescription $description,
        public private(set) PostAuthorName $authorName
    ) { }

    public static function Create(
        PostId $id,
        AuthorId $authorId,
        PostTitle $title,
        PostContent $content,
        PostDescription $description,
        PostAuthorName $authorName
    ): Post {
        $post = new self($id, $authorId, $title, $content, $description, $authorName);
        $post->record(
            new PostCreatedDomainEvent(
                $id->value(),
                $authorId->value(),
                $title->value(),
                $content->value(),
                $description->value(),
                $authorName->value()
            )
        );

        return $post;
    }

    public function updateAuthorName(PostAuthorName $authorName): void
    {
        if ($this->authorName->equals($authorName)) {
            return;
        }

        $this->authorName = $authorName;
    }

}
