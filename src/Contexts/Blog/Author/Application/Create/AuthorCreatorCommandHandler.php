<?php

namespace App\Contexts\Blog\Author\Application\Create;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Author\Domain\AuthorName;
use App\Contexts\Blog\Author\Domain\AuthorSurname;
use App\Shared\Domain\Bus\Command\CommandHandler;

readonly class AuthorCreatorCommandHandler implements CommandHandler
{
    public function __construct(
        private AuthorCreator $authorCreator
    ) {
    }

    public function __invoke(AuthorCreatorCommand $query): void
    {
        $this->authorCreator->__invoke(
            id: AuthorId::fromString($query->id),
            name: AuthorName::fromString($query->name),
            surname: AuthorSurname::fromString($query->surname)
        );
    }
}
