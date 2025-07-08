<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Command\Response;
use App\Shared\Infrastructure\Bus\Command\CommandNotRegisteredError;
use App\Shared\Infrastructure\Bus\Command\InMemorySymfonyCommandBus;
use App\Tests\Shared\Domain\WordMother;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

final class InMemorySymfonyCommandBusTest extends UnitTestCase
{
    private InMemorySymfonyCommandBus|null $commandBus;
    private object $response;

    protected function setUp(): void
    {
        parent::setUp();
        $this->response = $this->dumbResponse(WordMother::create());
        $this->commandBus = new InMemorySymfonyCommandBus([$this->commandHandler($this->response)]);
    }

    #[Test]
    public function itShouldBeAbleToHandleACommand(): void
    {
        $response = $this->commandBus->dispatch(new FakeCommand());
        $this->assertEquals($this->response->value, $response->value);
    }

    #[Test]
    public function itShouldRaiseAnExceptionDispatchingANonRegisteredCommand(): void
    {
        $this->expectException(CommandNotRegisteredError::class);

        $this->commandBus->dispatch($this->command());
    }

    private function commandHandler($response): object
    {
        return new class ($response) implements CommandHandler {
            public function __construct(private readonly Response $response)
            {
            }
            public function __invoke(FakeCommand $command): ?Response
            {
                return $this->response;
            }
        };
    }

    private function dumbResponse($response): object
    {
        return new class ($response) implements Response {
            public function __construct(public readonly string $value)
            {
            }
        };
    }

    private function command(): Command|MockInterface
    {
        return $this->mock(Command::class);
    }
}
