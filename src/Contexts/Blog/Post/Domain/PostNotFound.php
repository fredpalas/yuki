<?php

namespace App\Contexts\Blog\Post\Domain;

use App\Shared\Domain\DomainError;
use App\Shared\Domain\NotFoundExceptionInterface;

class PostNotFound extends DomainError implements NotFoundExceptionInterface
{
    public function __construct(private readonly PostId $postId)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'blog.post.not_found';
    }

    protected function errorMessage(): string
    {
        return sprintf('The post <%s> does not exist', $this->postId->value());
    }
}
