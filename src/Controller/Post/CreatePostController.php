<?php

namespace App\Controller\Post;

use App\Contexts\Blog\Author\Application\Find\AuthorFinderQuery;
use App\Contexts\Blog\Author\Application\Find\AuthorFinderQueryResponse;
use App\Contexts\Blog\Post\Application\Create\PostCreatorCommand;
use App\DTO\CreatePost;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\UuidGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreatePostController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly UuidGenerator $uuidGenerator,
        private readonly CommandBus $commandBus
    )
    {
    }

    #[Route('/post', name: 'app_post_create_post', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreatePost $createPost): \Symfony\Component\HttpFoundation\JsonResponse
    {
        /** @var AuthorFinderQueryResponse $author */
        $author = $this->queryBus->ask(new AuthorFinderQuery($createPost->authorId));
        $postId = $this->uuidGenerator->generate();
        $this->commandBus->dispatch(
            new PostCreatorCommand(
                id: $postId,
                authorId: $author->author->id,
                title: $createPost->title,
                content: $createPost->content,
                description: $createPost->description,
                authorName: $author->author->name . ' ' . $author->author->surname,
            )
        );

        return $this->json(
            [
                'id' => $postId,
                'authorId' => $author->author->id->value(),
                'title' => $createPost->title,
                'content' => $createPost->content,
                'description' => $createPost->description,
                'authorName' => sprintf(
                    '%s %s',
                    $author->author->name->value(),
                    $author->author->surname->value()
                ),
            ],
            Response::HTTP_CREATED
        );
    }
}
