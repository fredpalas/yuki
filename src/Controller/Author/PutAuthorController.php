<?php

namespace App\Controller\Author;

use App\DTO\PutAuthorRequest;
use App\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class PutAuthorController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus
    ) {
    }


    #[Route('/author/{id}', name: 'app_put_author', requirements: ['id' => Requirement::UUID], methods: ['PUT'])]
    public function __invoke(#[MapRequestPayload] PutAuthorRequest $putAuthorRequest, string $id): JsonResponse
    {
        $this->commandBus->dispatch(
            command: $putAuthorRequest->toCommand($id)
        );

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

}
