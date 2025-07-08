<?php

namespace App\Shared\Domain\Aggregate\Trait;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Aggregate\AggregateSubModel;
use App\Shared\Domain\Collection;
use App\Shared\Domain\ValueObject\ValueObject;
use ReflectionClass;
use ReflectionProperty;

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\reduce;

trait ToPrimitiveTrait
{
    public function toPrimitives(): array
    {
        $reflectedValueObjects = $this->getValueObjects();

        return reduce(function (array $primitives, ReflectionProperty $property) {
            $propName = $property->getName();
            $type = $property->getType();
            $propertyValue = $property->getValue($this);
            if (get_class($type) === 'ReflectionUnionType') {
                $types = $type->getTypes();
                $property = filter(
                    fn ($type) => $type->getName() == \Doctrine\Common\Collections\Collection::class,
                    $types
                );
                if (count($property) > 0) {
                    $property2 = filter(
                        fn ($type) => $type->getName() != \Doctrine\Common\Collections\Collection::class,
                        $types
                    );
                    $type = $property2[array_key_first($property2)]->getName();
                    $propertyValue = new ($type)($propertyValue->toArray());
                }
            }
            return array_merge($primitives, [
                $propName => match (true) {
                    $propertyValue instanceof ValueObject => $propertyValue->value(),
                    $propertyValue instanceof AggregateRoot,
                    $propertyValue instanceof AggregateSubModel => $propertyValue->toPrimitives(),
                    $propertyValue instanceof Collection => $propertyValue->toArrayPrimitive(),
                    default => $propertyValue,
                },
            ]);
        }, $reflectedValueObjects, []);
    }

    private function getValueObjects(): array
    {
        $reflectedEntity = new ReflectionClass($this);

        return $reflectedEntity->getProperties();
    }
}
