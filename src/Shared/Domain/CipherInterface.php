<?php

namespace App\Shared\Domain;

interface CipherInterface
{
    public function cipher(string $data): string;

    public function decipher(string $data): string;
}
