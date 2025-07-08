<?php

namespace App\Contexts\Blog\Author\Domain;

use App\Shared\Domain\DomainError;

class AuthorNotFound extends DomainError
{
    public function __construct(private readonly AuthorId $authorId)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'blog.author.not_found';
    }

    protected function errorMessage(): string
    {
        return sprintf('The author <%s> does not exist', $this->authorId->value());
    }

}
