<?php

namespace App\Tests\Unit\Contexts\Blog\Author\Domain;

use App\Contexts\Blog\Author\Domain\AuthorName;
use App\Tests\Shared\Domain\MotherCreator;

class AuthorNameMother
{
    public static function create(?string $value = null): AuthorName
    {
        return new AuthorName(
            $value ?? MotherCreator::random()->firstName()
        );
    }
}
