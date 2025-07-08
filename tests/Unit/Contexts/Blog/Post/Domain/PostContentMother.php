<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Post\Domain\PostContent;
use App\Tests\Shared\Domain\MotherCreator;

class PostContentMother
{
    public static function create(?string $content = null): PostContent
    {
        return new PostContent(
            $content ?? MotherCreator::random()->paragraph(6)
        );
    }
}
