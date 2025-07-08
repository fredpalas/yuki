<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Domain;

use App\Contexts\Blog\Post\Domain\PostId;
use App\Tests\Shared\Domain\UuidMother;

class PostIdMother
{
    public static function create(?string $id = null): PostId
    {
        return PostId::create($id ?? UuidMother::create());
    }
}
