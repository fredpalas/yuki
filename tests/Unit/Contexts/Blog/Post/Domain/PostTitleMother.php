<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Post\Domain\PostTitle;
use App\Tests\Shared\Domain\MotherCreator;

class PostTitleMother
{
    public static function create(?string $title = null): PostTitle
    {
        return new PostTitle(
            $title ?? MotherCreator::random()->sentence(2)
        );
    }
}
