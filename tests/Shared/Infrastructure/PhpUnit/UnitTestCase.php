<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\PhpUnit;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Command\Response as CommandResponse;
use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Domain\UuidGenerator;
use App\Tests\Shared\Domain\TestUtils;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Matcher\MatcherInterface;
use Mockery\MockInterface;

abstract class UnitTestCase extends MockeryTestCase
{
    private EventBus|MockInterface|null $eventBus;
    private UuidGenerator|MockInterface $uuidGenerator;
    private MockInterface|CommandBus $commandBus;
    private MockInterface|QueryBus $queryBus;

    protected function mock(string $className): MockInterface
    {
        return Mockery::mock($className);
    }

    protected function shouldPublishDomainEvent(DomainEvent ...$domainEvent): void
    {
        $events = array_map(fn (DomainEvent $event) => $this->similarTo($event), $domainEvent);
        $this->eventBus()
            ->shouldReceive('publish')
            ->once()
            ->withArgs($events)
            ->andReturnNull();
    }

    protected function shouldNotPublishDomainEvent(): void
    {
        $this->eventBus()
            ->shouldReceive('publish')
            ->withNoArgs()
            ->andReturnNull();
    }

    protected function eventBus(): EventBus|MockInterface
    {
        return $this->eventBus = $this->eventBus ?? $this->mock(EventBus::class);
    }

    protected function shouldGenerateUuid(string $uuid): void
    {
        $this->uuidGenerator()
            ->shouldReceive('generate')
            ->once()
            ->withNoArgs()
            ->andReturn($uuid);
    }

    protected function uuidGenerator(): UuidGenerator|MockInterface
    {
        return $this->uuidGenerator = $this->uuidGenerator ?? $this->mock(UuidGenerator::class);
    }

    protected function notify(DomainEvent $event, callable $subscriber): void
    {
        $subscriber($event);
    }

    protected function dispatch(Command $command, callable $commandHandler): void
    {
        $commandHandler($command);
    }

    protected function commandBus(): CommandBus|MockInterface
    {
        return $this->commandBus = $this->commandBus ?? $this->mock(CommandBus::class);
    }

    protected function shouldDispatchCommand(Command $command, ?CommandResponse $response = null): void
    {
        $this->commandBus()
            ->shouldReceive('dispatch')
            ->with($this->similarTo($command))
            ->andReturn($response)
            ->once();
    }

    protected function assertAskResponse(Response $expected, Query $query, callable $queryHandler): void
    {
        $actual = $queryHandler($query);

        $this->assertEquals($expected, $actual);
    }

    protected function assertAskThrowsException(string $expectedErrorClass, Query $query, callable $queryHandler): void
    {
        $this->expectException($expectedErrorClass);

        $queryHandler($query);
    }

    protected function isSimilar($expected, $actual): bool
    {
        return TestUtils::isSimilar($expected, $actual);
    }

    protected function assertSimilar($expected, $actual): void
    {
        TestUtils::assertSimilar($expected, $actual);
    }

    protected function assertIsSimilar($expected, $actual): void
    {
        static::assertThat($actual, TestUtils::assertSimilarConstraint($expected));
    }

    protected function similarTo($value, $delta = 0.0): MatcherInterface
    {
        return TestUtils::similarTo($value, $delta);
    }

    protected function queryBus(): MockInterface|QueryBus
    {
        return $this->queryBus = $this->queryBus ?? $this->mock(QueryBus::class);
    }

    protected function shouldAsk(Query $query, Response $response): void
    {
        $this->queryBus()
            ->shouldReceive('ask')
            ->with($this->similarTo($query))
            ->andReturn($response)
            ->once();
    }
}
