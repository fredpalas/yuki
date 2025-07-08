<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Post\Domain\PostDescription;
use App\Tests\Shared\Domain\MotherCreator;

class PostDescriptionMother
{
    public static function create(?string $description = null): PostDescription
    {
        return new PostDescription(
            $description ?? MotherCreator::random()->sentence(3)
        );
    }
}
