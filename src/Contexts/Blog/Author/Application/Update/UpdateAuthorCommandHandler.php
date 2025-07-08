<?php

namespace App\Contexts\Blog\Author\Application\Update;

use App\Contexts\Blog\Author\Application\Find\AuthorFinder;
use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Author\Domain\AuthorName;
use App\Contexts\Blog\Author\Domain\AuthorRepository;
use App\Contexts\Blog\Author\Domain\AuthorSurname;
use App\Shared\Domain\Bus\Command\CommandHandler;

class UpdateAuthorCommandHandler implements CommandHandler
{
    public function __construct(private AuthorUpdater $authorUpdater)
    {
    }

    public function __invoke(UpdateAuthorCommand $command): void
    {
        $this->authorUpdater->__invoke(
            id: new AuthorId($command->id),
            name: $command->name ? AuthorName::fromString($command->name) : null,
            surname: $command->surname ? AuthorSurname::fromString($command->surname) : null
        );
    }
}
