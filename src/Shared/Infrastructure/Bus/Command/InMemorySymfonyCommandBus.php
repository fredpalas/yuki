<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Command\Response;
use App\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Stamp\HandledStamp;

use const Lambdish\Phunctional\map;

final class InMemorySymfonyCommandBus implements CommandBus
{
    private readonly MessageBus $bus;

    public function __construct(private iterable $commandHandlers)
    {
    }

    public function dispatch(Command $command): ?Response
    {
        try {
            $stamp = $this->bus()->dispatch($command)->last(HandledStamp::class);
            return $stamp->getResult();
        } catch (NoHandlerForMessageException) {
            throw new CommandNotRegisteredError($command);
        } catch (HandlerFailedException $error) {
            throw $error->getPrevious() ?? $error;
        }
    }

    public function bus(): MessageBus
    {
        return $this->bus ?? $this->bus = new MessageBus(
            [
                new HandleMessageMiddleware(
                    new HandlersLocator(CallableFirstParameterExtractor::forCallables($this->commandHandlers))
                ),
            ]
        );
    }
}
