<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Application\Find;

use App\Contexts\Blog\Post\Application\Find\PostFinder;
use App\Contexts\Blog\Post\Application\Find\PostFinderQuery;
use App\Contexts\Blog\Post\Application\Find\PostFinderQueryHandler;
use App\Contexts\Blog\Post\Application\Find\PostFinderQueryResponse;
use App\Contexts\Blog\Post\Domain\PostNotFound;
use App\Contexts\Blog\Post\Domain\PostRepository;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Contexts\Blog\Post\Domain\PostMother;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class PostFinderQueryHandlerTest extends UnitTestCase
{
    private PostFinderQueryHandler $handler;
    private PostRepository| MockInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mock(PostRepository::class);
        $this->handler = new PostFinderQueryHandler(new PostFinder($this->repository));
    }

    public function testShouldFindAPost(): void
    {
        $post = PostMother::create();

        $this->repository->shouldReceive('search')
            ->once()
            ->with($this->similarTo($post->id))
            ->andReturn($post);

        $response = $this->handler->__invoke(new PostFinderQuery($post->id->value()));

        $this->assertIsSimilar($post, $response->post);
    }

    public function testShouldThrowExceptionWhenPostNotFound(): void
    {
        $postId = PostMother::create()->id;

        $this->repository->shouldReceive('search')
            ->once()
            ->with($this->similarTo($postId))
            ->andReturn(null);

        $this->expectException(PostNotFound::class);
        $this->handler->__invoke(new PostFinderQuery($postId->value()));
    }
}
