<?php

namespace App\Shared\Domain;

class NoTypeCollection extends Collection
{
    public function __construct(array $items = [])
    {
        parent::__construct([]);
        $this->items = $items;
    }

    protected function type(): string
    {
        return '';
    }
}
