<?php

namespace App\Contexts\Blog\Author\Infrastructure\Persistence\Doctrine;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;

class AuthorIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return AuthorId::class;
    }
}
