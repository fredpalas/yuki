<?php

namespace App\Contexts\Blog\Post\Application\Find;

use App\Contexts\Blog\Post\Domain\Post;
use App\Contexts\Blog\Post\Domain\PostId;
use App\Contexts\Blog\Post\Domain\PostNotFound;
use App\Contexts\Blog\Post\Domain\PostRepository;

class PostFinder
{
    public function __construct(private PostRepository $repository) { }

    public function __invoke(PostId $postId): Post
    {
        $post = $this->repository->search($postId);

        if (null === $post) {
            throw new PostNotFound($postId);
        }

        return $post;
    }
}
