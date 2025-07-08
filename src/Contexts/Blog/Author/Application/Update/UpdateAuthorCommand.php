<?php

namespace App\Contexts\Blog\Author\Application\Update;

use App\Shared\Domain\Bus\Command\Command;

readonly class UpdateAuthorCommand implements Command
{
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $surname = null
    ) { }
}
