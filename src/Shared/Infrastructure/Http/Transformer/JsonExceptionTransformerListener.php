<?php

namespace App\Shared\Infrastructure\Http\Transformer;

use App\Shared\Domain\BadRequestExceptionInterface;
use App\Shared\Domain\ForbiddenExceptionInterface;
use App\Shared\Domain\NotFoundExceptionInterface;
use App\Shared\Domain\UnauthenticatedExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

use function get_class;
use function in_array;
use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\first;
use function time;

class JsonExceptionTransformerListener
{
    public function __construct(
        protected RequestStack $requestStack,
        private readonly bool $debug = false
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $accept = $request->headers->get('Accept');
        $contentType = $request->headers->get('Content-Type');
        if (
            !$request->isXmlHttpRequest()
            && !in_array($accept, ['application/json', 'application/ld+json'], true)
            && !in_array($contentType, ['application/json', 'application/ld+json'], true)
        ) {
            return;
        }

        $exception = $event->getThrowable();

        $data = [
            'code' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exception->getMessage(),
        ];

        if ($this->debug) {
            $data['exception'] = [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ];
        }

        if ($this->renderNotFoundEntity($exception)) {
            $data['code'] = JsonResponse::HTTP_NOT_FOUND;
        }

        if ($this->renderConflictEntity($exception)) {
            $data['code'] = JsonResponse::HTTP_CONFLICT;
        }

        if ($this->renderBadRequestEntity($exception)) {
            $data['code'] = JsonResponse::HTTP_BAD_REQUEST;
        }
        if ($exception instanceof AccessDeniedHttpException || $this->renderForbiddenEntity($exception)) {
            $data['code'] = JsonResponse::HTTP_FORBIDDEN;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $data['code'] = $exception->getStatusCode();
        }

        if ($exception instanceof UnauthorizedHttpException || $this->renderUnauthorizedEntity($exception)) {
            $data['code'] = JsonResponse::HTTP_UNAUTHORIZED;
        }

        $event->setResponse($this->prepareResponse($data));
    }

    private function prepareResponse(array $data): JsonResponse
    {
        $response = new JsonResponse($data, $data['code']);
        $response->headers->set('X-Error-Code', $data['code']);
        $response->headers->set('X-Server-Time', time());

        return $response;
    }

    private function getNotFoundExceptions(): array
    {
        return [NotFoundExceptionInterface::class];
    }

    private function getConflictExceptions(): array
    {
        return [];
    }

    public function getBadRequestExceptions(): array
    {
        return [BadRequestExceptionInterface::class];
    }

    public function getForbiddenExceptions(): array
    {
        return [ForbiddenExceptionInterface::class];
    }

    public function getUnauthenticatedExceptions(): array
    {
        return [UnauthenticatedExceptionInterface::class];
    }

    protected function renderNotFoundEntity(Throwable $exception): bool
    {
        return !is_null(
            first(
                filter(
                    fn ($type) => $exception instanceof $type,
                    $this->getNotFoundExceptions()
                )
            )
        );
    }

    protected function renderConflictEntity(Throwable $exception): bool
    {
        return !is_null(
            first(
                filter(
                    fn ($type) => $exception instanceof $type,
                    $this->getConflictExceptions()
                )
            )
        );
    }

    protected function renderBadRequestEntity(Throwable $exception): bool
    {
        return !is_null(
            first(
                filter(
                    fn ($type) => $exception instanceof $type,
                    $this->getBadRequestExceptions()
                )
            )
        );
    }

    protected function renderForbiddenEntity(Throwable $exception): bool
    {
        return !is_null(
            first(
                filter(
                    fn ($type) => $exception instanceof $type,
                    $this->getForbiddenExceptions()
                )
            )
        );
    }

    protected function renderUnauthorizedEntity(Throwable $exception): bool
    {
        return !is_null(
            first(
                filter(
                    fn ($type) => $exception instanceof $type,
                    $this->getUnauthenticatedExceptions()
                )
            )
        );
    }
}
