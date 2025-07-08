<?php

namespace App\Tests\Unit\Contexts\Blog\Author\Application\Update;

use App\Contexts\Blog\Author\Application\Find\AuthorFinder;
use App\Contexts\Blog\Author\Application\Update\AuthorUpdater;
use App\Contexts\Blog\Author\Application\Update\UpdateAuthorCommand;
use App\Contexts\Blog\Author\Application\Update\UpdateAuthorCommandHandler;
use App\Contexts\Blog\Author\Domain\AuthorRepository;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorNameMother;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorSurnameMother;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class UpdateAuthorCommandHandlerTest extends UnitTestCase
{
    private UpdateAuthorCommandHandler $handler;
    private AuthorRepository| MockInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mock(AuthorRepository::class);
        $this->handler = new UpdateAuthorCommandHandler(
            new AuthorUpdater($this->repository, new AuthorFinder($this->repository), $this->eventBus()),
        );
    }

    public function testShouldUpdateAuthor(): void
    {
        $author = AuthorMother::create();
        $author->pullDomainEvents();
        $authorClone = clone $author;

        $newName = AuthorNameMother::create();
        $authorClone->updateName($newName);
        $newSurname = AuthorSurnameMother::create();
        $authorClone->updateSurname($newSurname);
        $this->shouldPublishDomainEvent(...$authorClone->pullDomainEvents());

        $this->repository
            ->shouldReceive('search')
            ->once()
            ->with($this->similarTo($author->id))
            ->andReturn($author);

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($this->similarTo($authorClone));

        $this->handler->__invoke(
            new UpdateAuthorCommand(
                $author->id->value(),
                $newName->value(),
                $newSurname->value()
            )
        );
    }

    public function testShouldUpdateAuthorName(): void
    {
        $author = AuthorMother::create();
        $author->pullDomainEvents();
        $authorClone = clone $author;

        $newName = AuthorNameMother::create();
        $authorClone->updateName($newName);
        $this->shouldPublishDomainEvent(...$authorClone->pullDomainEvents());

        $this->repository
            ->shouldReceive('search')
            ->once()
            ->with($this->similarTo($author->id))
            ->andReturn($author);

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($this->similarTo($authorClone));

        $this->handler->__invoke(
            new UpdateAuthorCommand(
                $author->id->value(),
                $newName->value(),
                $author->surname->value()
            )
        );
    }

    public function testShouldUpdateAuthorSurname(): void
    {
        $author = AuthorMother::create();
        $author->pullDomainEvents();
        $authorClone = clone $author;

        $newSurname = AuthorSurnameMother::create();
        $authorClone->updateSurname($newSurname);
        $this->shouldPublishDomainEvent(...$authorClone->pullDomainEvents());

        $this->repository
            ->shouldReceive('search')
            ->once()
            ->with($this->similarTo($author->id))
            ->andReturn($author);

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($this->similarTo($authorClone));

        $this->handler->__invoke(
            new UpdateAuthorCommand(
                $author->id->value(),
                $author->name->value(),
                $newSurname->value()
            )
        );
    }
}
