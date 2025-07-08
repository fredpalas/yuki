<?php

namespace App\Contexts\Blog\Author\Application\Find;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Shared\Domain\Bus\Query\QueryHandler;

class AuthorFinderQueryHandler implements QueryHandler
{
    public function __construct(
        private AuthorFinder $authorFinder
    ) {
    }

    public function __invoke(AuthorFinderQuery $query): AuthorFinderQueryResponse
    {
        $author = $this->authorFinder->__invoke(new AuthorId($query->authorId));

        return new AuthorFinderQueryResponse($author);
    }
}
