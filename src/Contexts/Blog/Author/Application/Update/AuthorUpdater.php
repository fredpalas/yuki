<?php

namespace App\Contexts\Blog\Author\Application\Update;

use App\Contexts\Blog\Author\Application\Find\AuthorFinder;
use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Author\Domain\AuthorName;
use App\Contexts\Blog\Author\Domain\AuthorRepository;
use App\Contexts\Blog\Author\Domain\AuthorSurname;
use App\Shared\Domain\Bus\Event\EventBus;

class AuthorUpdater
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private AuthorFinder $authorFinder,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(
        AuthorId $id,
        ?AuthorName $name = null,
        ?AuthorSurname $surname = null
    ): void {
        $author = $this->authorFinder->__invoke($id);

        if ($name) {
            $author->updateName($name);
        }

        if ($surname) {
            $author->updateSurname($surname);
        }

        $this->authorRepository->save($author);
        $this->eventBus->publish(...$author->pullDomainEvents());
    }

}
