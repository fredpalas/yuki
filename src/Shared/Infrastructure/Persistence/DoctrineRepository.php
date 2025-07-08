<?php

namespace App\Shared\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class DoctrineRepository
{
    protected static array $fields = [];
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }


    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    abstract protected function getEntityName(): string;

    protected function persist($entity): void
    {
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();
    }

    protected function remove($entity): void
    {
        $this->entityManager()->remove($entity);
        $this->entityManager()->flush();
    }

    protected function repository(): EntityRepository
    {
        return $this->entityManager->getRepository($this->getEntityName());
    }
}
