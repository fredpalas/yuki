<?php

namespace App\Contexts\Blog\Author\Application\Create;

use App\Contexts\Blog\Author\Domain\Author;
use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Author\Domain\AuthorName;
use App\Contexts\Blog\Author\Domain\AuthorRepository;
use App\Contexts\Blog\Author\Domain\AuthorSurname;

readonly class AuthorCreator
{
    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    public function __invoke(
        AuthorId $id,
        AuthorName $name,
        AuthorSurname $surname
    ) {
        $author = Author::create(
            id: $id,
            name: $name,
            surname: $surname
        );

        $this->authorRepository->save($author);
    }

}
