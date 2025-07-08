<?php

namespace App\Contexts\Blog\Author\Application\Find;

use App\Contexts\Blog\Author\Domain\Author;
use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Author\Domain\AuthorNotFound;
use App\Contexts\Blog\Author\Domain\AuthorRepository;

readonly class AuthorFinder
{
    public function __construct(
        private AuthorRepository $authorRepository
    ) {
    }

    public function __invoke(AuthorId $authorId): Author
    {
        $author = $this->authorRepository->search($authorId);

        if (null === $author) {
            throw new AuthorNotFound($authorId);
        }

        return $author;
    }
}
