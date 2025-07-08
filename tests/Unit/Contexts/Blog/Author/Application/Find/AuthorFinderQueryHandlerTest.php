<?php

namespace App\Tests\Unit\Contexts\Blog\Author\Application\Find;

use App\Contexts\Blog\Author\Application\Find\AuthorFinder;
use App\Contexts\Blog\Author\Application\Find\AuthorFinderQuery;
use App\Contexts\Blog\Author\Application\Find\AuthorFinderQueryHandler;
use App\Contexts\Blog\Author\Domain\AuthorNotFound;
use App\Contexts\Blog\Author\Domain\AuthorRepository;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class AuthorFinderQueryHandlerTest extends UnitTestCase
{
    private AuthorRepository| MockInterface $repository;
    private AuthorFinderQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mock(AuthorRepository::class);
        $this->handler = new AuthorFinderQueryHandler(new AuthorFinder($this->repository));
    }

    public function testShouldFindAuthor()
    {
        $author = AuthorMother::create();

        $this->repository
            ->shouldReceive('search')
            ->with($this->similarTo($author->id))
            ->once()
            ->andReturn($author);

        $response = $this->handler->__invoke(new AuthorFinderQuery($author->id->value()));

        $this->assertIsSimilar($author, $response->author);
    }

    public function testShouldThrowExceptionWhenAuthorNotFound()
    {
        $authorId = AuthorMother::create()->id;

        $this->repository
            ->shouldReceive('search')
            ->with($this->similarTo($authorId))
            ->once()
            ->andReturn(null);

        $this->expectException(AuthorNotFound::class);
        $this->handler->__invoke(new AuthorFinderQuery($authorId->value()));
    }
}
