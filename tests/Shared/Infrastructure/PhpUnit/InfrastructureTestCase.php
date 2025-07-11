<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\PhpUnit;

use App\Tests\Shared\Domain\TestUtils;
use App\Tests\Shared\Infrastructure\PhpUnit\Constraint\ConstraintIsSimilar;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class InfrastructureTestCase extends KernelTestCase
{
    abstract protected function kernelClass(): string;

    protected function setUp(): void
    {
        $_SERVER['KERNEL_CLASS'] = $this->kernelClass();

        self::bootKernel(['environment' => 'test']);

        parent::setUp();
    }

    protected function assertSimilar($expected, $actual): void
    {
        TestUtils::assertSimilar($expected, $actual);
    }

    protected function assertIsSimilar($expected, $actual): void
    {
        static::assertThat($actual, TestUtils::assertSimilarConstraint($expected));
    }

    protected function isSimilar($expected, $actual): bool
    {
        return TestUtils::isSimilar($expected, $actual);
    }

    protected function similarTo($value, $delta = 0.0): ConstraintIsSimilar
    {
        return new ConstraintIsSimilar($value, $delta);
    }

    protected function service(string $id): mixed
    {
        return self::getContainer()->get($id);
    }

    protected function parameter($parameter): mixed
    {
        return self::getContainer()->getParameter($parameter);
    }

    protected function clearUnitOfWork(): void
    {
        $this->service(EntityManager::class)->clear();
    }

    protected function eventually(callable $fn, $totalRetries = 3, $timeToWaitOnErrorInSeconds = 1, $attempt = 0): void
    {
        try {
            $fn();
        } catch (\Throwable $error) {
            if ($totalRetries === $attempt) {
                throw $error;
            }

            sleep($timeToWaitOnErrorInSeconds);

            $this->eventually($fn, $totalRetries, $timeToWaitOnErrorInSeconds, $attempt + 1);
        }
    }
}
