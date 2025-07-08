<?php

namespace App\Contexts\Blog\Post\Application\Create;

use App\Contexts\Blog\Author\Domain\AuthorId;
use App\Contexts\Blog\Post\Domain\PostAuthorName;
use App\Contexts\Blog\Post\Domain\PostContent;
use App\Contexts\Blog\Post\Domain\PostDescription;
use App\Contexts\Blog\Post\Domain\PostId;
use App\Contexts\Blog\Post\Domain\PostTitle;
use App\Shared\Domain\Bus\Command\CommandHandler;

readonly class PostCreatorCommandHandler implements CommandHandler
{
    public function __construct(private PostCreator $postCreator) { }

    public function __invoke(PostCreatorCommand $command): void
    {
        $this->postCreator->__invoke(
            id: new PostId($command->id),
            authorId: new AuthorId($command->authorId),
            title: new PostTitle($command->title),
            content: new PostContent($command->content),
            description: new PostDescription($command->description),
            authorName: new PostAuthorName($command->authorName)
        );
    }
}
