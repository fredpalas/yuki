<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\PhpUnit\Comparator;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Tests\Shared\Domain\TestUtils;
use ReflectionObject;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

final class DomainEventSimilarComparator extends Comparator
{
    private static array $ignoredAttributes = ['eventId', 'occurredOn'];
    private Exporter $exporter;

    public function __construct()
    {
        $this->exporter = new Exporter();
    }

    public function accepts($expected, $actual): bool
    {
        $domainEventRootClass = DomainEvent::class;

        return $expected instanceof $domainEventRootClass && $actual instanceof $domainEventRootClass;
    }

    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false): void
    {
        if (!$this->areSimilar($expected, $actual)) {
            throw new ComparisonFailure(
                $expected,
                $actual,
                $this->exporter->export($expected),
                $this->exporter->export($actual),
                'Failed asserting the events are equal.'
            );
        }
    }

    private function areSimilar(DomainEvent $expected, DomainEvent $actual): bool
    {
        if (!$this->areTheSameClass($expected, $actual)) {
            return false;
        }

        return $this->propertiesAreSimilar($expected, $actual);
    }

    private function areTheSameClass(DomainEvent $expected, DomainEvent $actual): bool
    {
        return $expected::class === $actual::class;
    }

    private function propertiesAreSimilar(DomainEvent $expected, DomainEvent $actual): bool
    {
        $expectedReflected = new ReflectionObject($expected);
        $actualReflected   = new ReflectionObject($actual);

        foreach ($expectedReflected->getProperties() as $expectedReflectedProperty) {
            if (!in_array($expectedReflectedProperty->getName(), self::$ignoredAttributes, false)) {
                $actualReflectedProperty = $actualReflected->getProperty($expectedReflectedProperty->getName());

                $expectedProperty = $expectedReflectedProperty->getValue($expected);
                $actualProperty   = $actualReflectedProperty->getValue($actual);

                if (!TestUtils::isSimilar($expectedProperty, $actualProperty)) {
                    return false;
                }
            }
        }

        return true;
    }
}
