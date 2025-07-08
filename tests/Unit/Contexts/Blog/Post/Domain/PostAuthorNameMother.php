<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Post\Domain\PostAuthorName;
use App\Tests\Shared\Domain\MotherCreator;

class PostAuthorNameMother
{
    public static function create(?string $authorName = null): PostAuthorName
    {
        return new PostAuthorName(
            $authorName ?? MotherCreator::random()->name()
        );
    }
}
