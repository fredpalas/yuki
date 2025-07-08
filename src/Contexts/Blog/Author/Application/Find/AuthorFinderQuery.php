<?php

namespace App\Contexts\Blog\Author\Application\Find;

use App\Shared\Domain\Bus\Query\Query;

class AuthorFinderQuery implements Query
{
    public function __construct(
        public string $authorId,
    ) {
    }
}
