<?php

namespace App\Tests\Unit\Contexts\Blog\Author\Domain;

use App\Contexts\Blog\Author\Domain\AuthorSurname;
use App\Tests\Shared\Domain\MotherCreator;

class AuthorSurnameMother
{
    public static function create(?string $value = null): AuthorSurname
    {
        return new AuthorSurname(
            $value ?? MotherCreator::random()->lastName()
        );
    }
}
