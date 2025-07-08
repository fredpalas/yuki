<?php

namespace App\Tests\Unit\Contexts\Blog\Author\Domain;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Tests\Shared\Domain\UuidMother;

class AuthorIdMother
{
    public static function create(?string $value = null): AuthorId
    {
        return new AuthorId($value ?? UuidMother::create());
    }
}
