<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain;

use App\Tests\Shared\Infrastructure\Mockery\MatcherIsSimilar;
use App\Tests\Shared\Infrastructure\PhpUnit\Constraint\ConstraintIsSimilar;

final class TestUtils
{
    public static function isSimilar($expected, $actual): bool
    {
        $constraint = new ConstraintIsSimilar($expected);

        return $constraint->evaluate($actual, '', true);
    }

    public static function assertSimilar($expected, $actual): void
    {
        $constraint = new ConstraintIsSimilar($expected);

        $constraint->evaluate($actual);
    }

    public static function similarTo($value, $delta = 0.0): MatcherIsSimilar
    {
        return new MatcherIsSimilar($value, $delta);
    }

    public static function assertSimilarConstraint($expected): ConstraintIsSimilar
    {
        return new ConstraintIsSimilar($expected);
    }
}
