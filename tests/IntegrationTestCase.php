<?php

namespace App\Tests;

use App\Kernel;

class IntegrationTestCase extends InfrastructureTestCase
{
    protected function kernelClass(): string
    {
        return Kernel::class;
    }
}
