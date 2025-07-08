<?php

namespace App\Contexts\Blog\Author\Application\Find;

use App\Contexts\Blog\Author\Domain\Author;
use App\Shared\Domain\Bus\Query\Response;

readonly class AuthorFinderQueryResponse implements Response
{
    public function __construct(public Author $author) {
    }
}
