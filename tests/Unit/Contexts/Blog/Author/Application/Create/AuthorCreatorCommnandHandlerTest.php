<?php

namespace App\Tests\Unit\Contexts\Blog\Author\Application\Create;

use App\Contexts\Blog\Author\Application\Create\AuthorCreator;
use App\Contexts\Blog\Author\Application\Create\AuthorCreatorCommand;
use App\Contexts\Blog\Author\Application\Create\AuthorCreatorCommandHandler;
use App\Contexts\Blog\Author\Domain\AuthorRepository;
use App\Tests\Shared\Domain\MotherCreator;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use Mockery\MockInterface;

class AuthorCreatorCommnandHandlerTest extends UnitTestCase
{
    private MockInterface|AuthorRepository $repository;
    private AuthorCreatorCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->mock(AuthorRepository::class);
        $this->handler = new AuthorCreatorCommandHandler(
            new AuthorCreator($this->repository)
        );
    }

    public function testShouldCreateAuthor(): void
    {
        $query = new AuthorCreatorCommand(
            MotherCreator::random()->uuid(),
            MotherCreator::random()->name(),
            MotherCreator::random()->lastName()
        );

        $author = AuthorMother::create(
            id: $query->id,
            name: $query->name,
            surname: $query->surname
        );

        $this->repository->shouldReceive('save')
            ->once()
            ->with($this->similarTo($author));

        $this->handler->__invoke($query);
    }
}
