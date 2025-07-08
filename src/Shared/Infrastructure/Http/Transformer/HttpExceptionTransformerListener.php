<?php

namespace App\Shared\Infrastructure\Http\Transformer;

use App\Shared\Domain\BadRequestExceptionInterface;
use App\Shared\Domain\ForbiddenExceptionInterface;
use App\Shared\Domain\NotFoundExceptionInterface;
use App\Shared\Domain\UnauthenticatedExceptionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\first;

class HttpExceptionTransformerListener
{
    public function __construct(
        protected RequestStack $requestStack,
        private readonly bool $debug = false
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $exception = $event->getThrowable();

        if (str_contains($request->headers->get('Accept', ''), 'application/json')) {
            return;
        }

        $data = [
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exception->getMessage(),
        ];

        if ($this->debug) {
            $data['exception'] = [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ];
        }

        if ($this->renderNotFoundEntity($exception)) {
            $data['code'] = Response::HTTP_NOT_FOUND;
        }

        if ($this->renderConflictEntity($exception)) {
            $data['code'] = Response::HTTP_CONFLICT;
        }

        if ($this->renderBadRequestEntity($exception)) {
            $data['code'] = Response::HTTP_BAD_REQUEST;
        }
        if ($this->renderForbiddenEntity($exception)) {
            $data['code'] = Response::HTTP_FORBIDDEN;
        }

        if ($this->renderUnauthorizedEntity($exception)) {
            $data['code'] = Response::HTTP_UNAUTHORIZED;
        }

        if ($exception instanceof HttpExceptionInterface) {
            return;
        }

        if (!isset($data['code'])) {
            return;
        }

        $event->setThrowable(HttpException::fromStatusCode($data['code'], $data['message'], $exception));
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
        return [BadRequestExceptionInterface::class, AccessDeniedHttpException::class];
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
