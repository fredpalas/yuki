<?php

namespace App\Contexts\Blog\Post\Application\Find;

use App\Contexts\Blog\Post\Domain\PostId;
use App\Contexts\Blog\Post\Domain\PostNotFound;
use App\Contexts\Blog\Post\Domain\PostRepository;
use App\Shared\Domain\Bus\Query\QueryHandler;

class PostFinderQueryHandler implements QueryHandler
{
    public function __construct(private PostFinder $finder) { }

    public function __invoke(PostFinderQuery $query): PostFinderQueryResponse
    {
        return new PostFinderQueryResponse(
            $this->finder->__invoke(new PostId($query->postId))
        );
    }

}
