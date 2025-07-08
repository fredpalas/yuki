<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthCheckController
{
    #[Route('/health', name: 'app_health_check', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response(
            'OK',
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain']
        );
    }

}
