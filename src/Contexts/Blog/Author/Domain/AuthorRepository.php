<?php

namespace App\Contexts\Blog\Author\Domain;

interface AuthorRepository
{
    public function save(Author $author): void;

    public function search(AuthorId $id): ?Author;
}
