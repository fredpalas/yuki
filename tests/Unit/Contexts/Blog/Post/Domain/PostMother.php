<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Post\Domain\Post;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorIdMother;

class PostMother
{
    public static function create(
        ?string $id = null,
        ?string $authorId = null,
        ?string $title = null,
        ?string $content = null,
        ?string $description = null,
        ?string $authorName = null,
    ): Post {
        return Post::create(
            PostIdMother::create($id),
            AuthorIdMother::create($authorId),
            PostTitleMother::create($title),
            PostContentMother::create($content),
            PostDescriptionMother::create($description),
            PostAuthorNameMother::create($authorName)
        );
    }
}
