<?php

namespace App\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Author\Domain\AuthorId;

interface PostRepository
{
    /**
     * @param Post $post
     * @return void
     */
    public function save(Post $post): void;

    public function search(PostId $id): ?Post;

    public function searchByAuthorId(AuthorId $authorId): array;
}
