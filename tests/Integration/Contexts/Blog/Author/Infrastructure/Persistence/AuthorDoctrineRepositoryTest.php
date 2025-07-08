<?php

namespace App\Tests\Integration\Contexts\Blog\Author\Infrastructure\Persistence;

use App\Contexts\Blog\Author\Infrastructure\Persistence\AuthorDoctrineRepository;
use App\Tests\IntegrationTestCase;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use PHPUnit\Framework\TestCase;

class AuthorDoctrineRepositoryTest extends IntegrationTestCase
{
    use RecreateDatabaseTrait;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        parent::setUp();

        $this->repository = self::service(AuthorDoctrineRepository::class);
    }

    public function testShouldSaveAuthor(): void
    {
        $author = AuthorMother::create();

        $this->repository->save($author);

        $this->clearUnitOfWork();

        $this->eventually(
            fn () => $this->assertIsSimilar($author, $this->repository->search($author->id)),
            5
        );
    }

}
