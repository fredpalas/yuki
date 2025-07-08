<?php

namespace App\Contexts\Blog\Author\Application\Create;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Query\Query;

readonly class AuthorCreatorCommand implements Command
{
    public function __construct(
        public string $id,
        public string $name,
        public string $surname
    ) {
    }
}
