<?php

namespace App\Controller\Author;

use App\Contexts\Blog\Author\Application\Create\AuthorCreatorCommand;
use App\DTO\CreateAuthorRequest;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\UuidGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PostAuthorController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus,
        private UuidGenerator $uuidGenerator
    ) {
    }


    #[Route('/author', name: 'app_post_author', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateAuthorRequest $createAuthorRequest): JsonResponse
    {
        $id = $this->uuidGenerator->generate();
        $this->commandBus->dispatch(
            new AuthorCreatorCommand(
                id: $id,
                name: $createAuthorRequest->name,
                surname: $createAuthorRequest->surname
            )
        );

        return $this->json(
            [
                'id' => $id,
                'name' => $createAuthorRequest->name,
                'surname' => $createAuthorRequest->surname,
            ],
            Response::HTTP_CREATED
        );
    }

}
