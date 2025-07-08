<?php

namespace App\Contexts\Blog\Post\Application\Create;

use App\Shared\Domain\Bus\Command\Command;

readonly class PostCreatorCommand implements Command
{
    public function __construct(
        public string $id,
        public string $authorId,
        public string $title,
        public string $content,
        public string $description,
        public string $authorName
    ) { }

}
