<?php

namespace App\Contexts\Blog\Post\Application\Find;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Post\Domain\PostRepository;

class PostsFinderByAuthorId
{
    public function __construct(private PostRepository $postRepository) { }

    public function __invoke(AuthorId $authorId): array
    {
        $posts = $this->postRepository->searchByAuthorId($authorId);

        if (empty($posts)) {
            return [];
        }

        return $posts;
    }

}
