<?php

namespace App\Shared\Domain;

interface CodeGenerator
{
    public function generate(string $text): string;
}
