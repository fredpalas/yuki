<?php

namespace App\Contexts\Blog\Post\Application\Find;

use App\Shared\Domain\Bus\Query\Query;

readonly class PostFinderQuery implements Query
{
    public function __construct(
        public string $postId,
    ) {
    }
}
