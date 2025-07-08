<?php

namespace App\Tests\Unit\Contexts\Blog\Author\Domain;

use App\Contexts\Blog\Author\Domain\Author;
use App\Tests\Shared\Domain\MotherCreator;
use App\Tests\Shared\Domain\UuidMother;

class AuthorMother
{
    public static function create(
        ?string $id = null,
        ?string $name = null,
        ?string $surname = null
    ): Author {
        return Author::create(
            AuthorIdMother::create($id),
            AuthorNameMother::create($name),
            AuthorSurnameMother::create($surname)
        );
    }

}
