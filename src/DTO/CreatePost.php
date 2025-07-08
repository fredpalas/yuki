<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreatePost
{
    public function __construct(
        #[Assert\NotBlank, Assert\Type("string"), Assert\Length(min: 1, max: 255)]
        public string $title,
        #[Assert\Uuid, Assert\NotBlank]
        public string $authorId,
        #[Assert\NotBlank, Assert\Type("string")]
        public string $content,
        #[Assert\NotBlank, Assert\Type("string"), Assert\Length(min: 1, max: 1000)]
        public string $description = '',
    ) {
    }
}
