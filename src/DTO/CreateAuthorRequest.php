<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateAuthorRequest
{
    public function __construct(
        #[Assert\NotBlank, Assert\Type("string"), Assert\Length(min: 1, max: 255)]
        public string $name,
        #[Assert\NotBlank, Assert\Type("string"), Assert\Length(min: 1, max: 255)]
        public string $surname
    ) {
    }
}
