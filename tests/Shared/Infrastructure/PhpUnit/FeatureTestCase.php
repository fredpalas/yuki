<?php

namespace App\Tests\Shared\Infrastructure\PhpUnit;

use App\Tests\Shared\Domain\TestUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

abstract class FeatureTestCase extends WebTestCase
{
    protected function assertSimilar($expected, $actual): void
    {
        TestUtils::assertSimilar($expected, $actual);
    }

    protected function assertIsSimilar($expected, $actual): void
    {
        static::assertThat($actual, TestUtils::assertSimilarConstraint($expected));
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
        } catch (Throwable $error) {
            if ($totalRetries === $attempt) {
                throw $error;
            }

            sleep($timeToWaitOnErrorInSeconds);

            $this->eventually($fn, $totalRetries, $timeToWaitOnErrorInSeconds, $attempt + 1);
        }
    }

    protected function assertArrayContains(mixed $value, array $actual): void
    {
        \Lambdish\Phunctional\each(
            fn ($el) => in_array($value, $el, true),
            $actual
        );
    }
}
