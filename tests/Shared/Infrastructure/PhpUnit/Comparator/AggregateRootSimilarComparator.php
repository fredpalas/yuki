<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\PhpUnit\Comparator;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Tests\Shared\Domain\TestUtils;
use ReflectionObject;
use SebastianBergmann\Comparator\Comparator;

use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

final class AggregateRootSimilarComparator extends Comparator
{
    private Exporter $exporter;

    public function __construct() {
        $this->exporter = new Exporter();
    }

    public function accepts($expected, $actual): bool
    {
        $aggregateRootClass = AggregateRoot::class;

        return $expected instanceof $aggregateRootClass && $actual instanceof $aggregateRootClass;
    }

    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false): void
    {
        $actualEntity = clone $actual;
        $actualEntity->pullDomainEvents();

        if (!$this->aggregateRootsAreSimilar($expected, $actualEntity)) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $this->exporter->export($expected),
                $this->exporter->export($actual),
                'Failed asserting the aggregate roots are equal.'
            );
        }
    }

    private function aggregateRootsAreSimilar(AggregateRoot $expected, AggregateRoot $actual): bool
    {
        if (!$this->aggregateRootsAreTheSameClass($expected, $actual)) {
            return false;
        }

        return $this->aggregateRootPropertiesAreSimilar($expected, $actual);
    }

    private function aggregateRootsAreTheSameClass(AggregateRoot $expected, AggregateRoot $actual): bool
    {
        return $expected::class === $actual::class;
    }

    private function aggregateRootPropertiesAreSimilar(AggregateRoot $expected, AggregateRoot $actual): bool
    {
        $expectedReflected = new ReflectionObject($expected);
        $actualReflected   = new ReflectionObject($actual);

        foreach ($expectedReflected->getProperties() as $expectedReflectedProperty) {
            $actualReflectedProperty = $actualReflected->getProperty($expectedReflectedProperty->getName());

            $expectedProperty = $expectedReflectedProperty->getValue($expected);
            $actualProperty   = $actualReflectedProperty->getValue($actual);

            if (!TestUtils::isSimilar($expectedProperty, $actualProperty)) {
                return false;
            }
        }

        return true;
    }
}
