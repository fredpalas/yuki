<?php

namespace App\Contexts\Blog\Post\Infrastructure\Persistence;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Post\Domain\Post;
use App\Contexts\Blog\Post\Domain\PostId;
use App\Contexts\Blog\Post\Domain\PostRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

class PostDoctrineRepository extends DoctrineRepository implements PostRepository
{
    public function save(Post $post): void
    {
        $this->persist($post);
    }

    public function search(PostId $id): ?Post
    {
        return $this->repository()->find($id->value());
    }


    public function searchByAuthorId(AuthorId $authorId): array
    {
        return $this->repository()->findBy(['authorId' => $authorId->value()]);
    }

    protected function getEntityName(): string
    {
        return Post::class;
    }
}
