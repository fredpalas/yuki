<?php

namespace App\Controller\Post;

use App\Contexts\Blog\Author\Application\Find\AuthorFinderQueryResponse;
use App\Contexts\Blog\Post\Application\Find\PostFinderQuery;
use App\Contexts\Blog\Post\Application\Find\PostFinderQueryResponse;
use App\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class GetPostController extends AbstractController
{
    public function __construct(
        private QueryBus $queryBus,
        private SerializerInterface $serializer
    ) { }

    #[Route('/post/{id}.{_format}', name: 'app_get_post', requirements: [
        'id' => Requirement::UUID,
        '_format' => 'json|xml',
    ], methods: ['GET'])]
    public function __invoke(string $id, string $_format = 'json'): Response
    {
        $query = new PostFinderQuery($id);
        /** @var PostFinderQueryResponse $response */
        $response = $this->queryBus->ask($query);
        $post = $response->post;

        return new Response(
            $this->serializer->serialize(
                [
                    'id' => $post->id->value(),
                    'authorId' => $post->authorId->value(),
                    'title' => $post->title->value(),
                    'content' => $post->content->value(),
                    'description' => $post->description->value(),
                    'authorName' => $post->authorName->value(),
                ],
                $_format
            ),
            Response::HTTP_OK,
            ['Content-Type' => 'application/' . $_format]
        );
    }
}
