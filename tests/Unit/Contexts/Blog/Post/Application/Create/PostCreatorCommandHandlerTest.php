<?php

namespace App\Tests\Unit\Contexts\Blog\Post\Application\Create;

use App\Contexts\Blog\Post\Application\Create\PostCreator;
use App\Contexts\Blog\Post\Application\Create\PostCreatorCommand;
use App\Contexts\Blog\Post\Application\Create\PostCreatorCommandHandler;
use App\Contexts\Blog\Post\Domain\PostRepository;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Unit\Contexts\Blog\Post\Domain\PostMother;
use Mockery\MockInterface;

class PostCreatorCommandHandlerTest extends UnitTestCase
{
    private MockInterface|PostRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->mock(PostRepository::class);
        $this->handler = new PostCreatorCommandHandler(
            new PostCreator($this->repository)
        );
    }

    public function testShouldCreateAPost(): void
    {
        $post = PostMother::create();

        $this->repository->shouldReceive('save')
            ->once()
            ->with($this->similarTo($post))
            ->andReturnNull();

        $this->handler->__invoke(
            new PostCreatorCommand(
                id: $post->id->value(),
                authorId: $post->authorId->value(),
                title: $post->title->value(),
                content: $post->content->value(),
                description: $post->description->value(),
                authorName: $post->authorName->value()
            )
        );
    }
}
