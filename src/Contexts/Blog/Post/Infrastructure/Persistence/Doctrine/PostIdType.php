<?php

namespace App\Contexts\Blog\Post\Infrastructure\Persistence\Doctrine;

use App\Contexts\Blog\Post\Domain\PostId;
use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;

class PostIdType extends UuidType
{
    protected function typeClassName(): string
    {
        return PostId::class;
    }
}
