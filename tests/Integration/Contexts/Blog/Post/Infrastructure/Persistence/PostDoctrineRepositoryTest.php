<?php

namespace App\Tests\Integration\Contexts\Blog\Post\Infrastructure\Persistence;

use App\Contexts\Blog\Post\Infrastructure\Persistence\PostDoctrineRepository;
use App\Tests\IntegrationTestCase;
use App\Tests\Unit\Contexts\Blog\Post\Domain\PostMother;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use PHPUnit\Framework\TestCase;

class PostDoctrineRepositoryTest extends IntegrationTestCase
{
    use RecreateDatabaseTrait;

    private PostDoctrineRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);
        parent::setUp();

        $this->repository = self::service(PostDoctrineRepository::class);
    }

    public function testShouldSavePost(): void
    {
        $post = PostMother::create();

        $this->repository->save($post);

        $this->clearUnitOfWork();

        $this->eventually(
            fn () => $this->assertIsSimilar($post, $this->repository->search($post->id)),
            5
        );
    }

    public function testShouldSearchPostByAuthorId(): void
    {
        $post = PostMother::create();

        $this->repository->save($post);

        $this->clearUnitOfWork();

        $this->eventually(
            fn () => $this->assertIsSimilar($post, $this->repository->searchByAuthorId($post->authorId)[0]),
            5
        );
    }
}
