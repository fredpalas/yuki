<?php

namespace App\Shared\Infrastructure\Doctrine\Listeners;

use App\Shared\Domain\ContainsNullableEmbeddable;
use App\Shared\Domain\Nullable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use ReflectionObject;

#[AsDoctrineListener('postLoad', 255)]
final class NullableEmbeddable
{
    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        $reflector = new \ReflectionClass($entity);
        $attributes = $reflector->getAttributes(ContainsNullableEmbeddable::class);
        if (empty($attributes)) {
            return;
        }

        $objectReflection = new ReflectionObject($entity);
        foreach ($objectReflection->getProperties() as $property) {
            $propertyAttributes = $property->getAttributes(Nullable::class);
            if (empty($propertyAttributes)) {
                continue;
            }
            $value = $property->getValue($entity);
            if ($this->allPropertiesAreNull($value)) {
                $property->setValue($entity, null);
            }
        }
    }

    private function allPropertiesAreNull(mixed $object): bool
    {
        $objectReflection = new ReflectionObject($object);
        foreach ($objectReflection->getProperties() as $property) {
            if ($property->isInitialized($object) && null !== $property->getValue($object)) {
                return false;
            }
        }

        return true;
    }
}
