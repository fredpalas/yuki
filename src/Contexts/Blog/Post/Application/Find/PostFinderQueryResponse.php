<?php

namespace App\Contexts\Blog\Post\Application\Find;

use App\Contexts\Blog\Post\Domain\Post;
use App\Shared\Domain\Bus\Query\Response;

readonly class PostFinderQueryResponse implements Response
{
    public function __construct(public Post $post)
    {
    }
}
