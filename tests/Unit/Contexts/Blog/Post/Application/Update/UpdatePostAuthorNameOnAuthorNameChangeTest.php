<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Application\Update;

use App\Contexts\Blog\Author\Domain\AuthorNameUpdatedDomainEvent;
use App\Contexts\Blog\Post\Application\Find\PostsFinderByAuthorId;
use App\Contexts\Blog\Post\Application\Update\UpdatePostAuthorNameOnAuthorNameChange;
use App\Contexts\Blog\Post\Domain\PostRepository;
use App\Tests\Shared\Domain\MotherCreator;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Contexts\Blog\Author\Domain\AuthorMother;
use App\Tests\Unit\Contexts\Blog\Post\Domain\PostMother;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class UpdatePostAuthorNameOnAuthorNameChangeTest extends UnitTestCase
{
    private UpdatePostAuthorNameOnAuthorNameChange $updater;
    private PostRepository| MockInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mock(PostRepository::class);
        $this->updater = new UpdatePostAuthorNameOnAuthorNameChange(
            new PostsFinderByAuthorId($this->repository),
            $this->repository
        );
    }

    public function testShouldUpdateAuthorNameInPosts(): void
    {
        $author = AuthorMother::create();
        $post = PostMother::create(
            authorName: sprintf('%s %s)', $author->name->value(), $author->surname->value()),
        );
        $name = MotherCreator::random()->name();
        $event = new AuthorNameUpdatedDomainEvent(
            $author->id->value(),
            $name,
            $author->name->value()
        );

        $this->repository
            ->shouldReceive('searchByAuthorId')
            ->once()
            ->with($this->similarTo($author->id))
            ->andReturn([$post]);
        $postClone = clone $post;

        $postClone->updateAuthorName(
            $postClone->authorName->replaceNeedle(
                $name,
                $author->name->value()
            )
        );

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($this->similarTo($postClone));

        $this->updater->__invoke($event);
    }

    public function testShouldNotUpdateAuthorNameInPostsIfNameIsTheSame(): void
    {
        $author = AuthorMother::create();
        $post = PostMother::create(
            authorName: sprintf('%s %s)', $author->name->value(), $author->surname->value()),
        );
        $event = new AuthorNameUpdatedDomainEvent(
            $author->id->value(),
            $author->name->value(),
            $author->name->value()
        );

        $this->repository
            ->shouldReceive('searchByAuthorId')
            ->once()
            ->with($this->similarTo($author->id))
            ->andReturn([$post]);

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($this->similarTo($post));

        $this->updater->__invoke($event);
    }
}
