<?php

namespace App\Contexts\Blog\Author\Infrastructure\Persistence;

use App\Contexts\Blog\Author\Domain\Author;
use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Author\Domain\AuthorRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

class AuthorDoctrineRepository extends DoctrineRepository implements AuthorRepository
{
    public function save(Author $author): void
    {
        $this->persist($author);
    }

    public function search(AuthorId $id): ?Author
    {
        return $this->repository()->find($id->value());
    }

    protected function getEntityName(): string
    {
        return Author::class;
    }
}
