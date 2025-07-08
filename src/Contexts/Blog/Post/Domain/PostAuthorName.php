<?php

namespace App\Contexts\Blog\Post\Domain;

use App\Shared\Domain\ValueObject\StringValueObject;

class PostAuthorName extends StringValueObject
{
    public function replaceNeedle(string $value, string $oldValue): PostAuthorName
    {
        return new self(
            str_contains($this->value, $oldValue) ?
                str_replace($oldValue, $value, $this->value) :
            $this->value
        );
    }
}
