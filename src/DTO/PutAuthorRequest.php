<?php

namespace App\DTO;

use App\Contexts\Blog\Author\Application\Update\UpdateAuthorCommand;
use Symfony\Component\Validator\Constraints as Assert;

class PutAuthorRequest
{
    public function __construct(
        #[Assert\Type("string"), Assert\Length(min: 1, max: 255), Assert\NotBlank(allowNull: true)]
        public ?string $name = null,
        #[Assert\Type("string"), Assert\Length(min: 1, max: 255), Assert\NotBlank(allowNull: true)]
        public ?string $surname = null
    ) {
    }

    public function toCommand(string $id): UpdateAuthorCommand
    {
        return new UpdateAuthorCommand(
            id: $id,
            name: $this->name,
            surname: $this->surname
        );
    }

}
