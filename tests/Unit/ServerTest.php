<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use webignition\DockerTcpCliProxy\Model\Command;
use webignition\DockerTcpCliProxy\Model\CommandResult;
use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Server;
use webignition\DockerTcpCliProxy\Services\ClientHandler;
use webignition\DockerTcpCliProxy\Services\ClientHandlerFactory;

class ServerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private int $commandIndex = 0;
    private int $resultIndex = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandIndex = 0;
        $this->resultIndex = 0;
    }

    /**
     * @dataProvider handleClientDataProvider
     */
    public function testHandleClient(ClientHandler $clientHandler)
    {
        $server = new Server(
            Mockery::mock(ListenSocket::class),
            $this->createClientHandlerFactory($clientHandler)
        );

        $server->handleClient();
    }

    public function handleClientDataProvider(): array
    {
        $lsCommandResult = new CommandResult(0, '.');

        return [
            'single quit command' => [
                'clientHandler' => $this->createClientHandler(
                    [
                        new Command(Command::CLOSE_CLIENT_CONNECTION_COMMAND),
                    ],
                    [
                    ],
                ),
            ],
            'single executable command, single quit command' => [
                'clientHandler' => $this->createClientHandler(
                    [
                        $this->mockCommandExecute(new Command('ls'), $lsCommandResult),
                        new Command(Command::CLOSE_CLIENT_CONNECTION_COMMAND),
                    ],
                    [
                        $lsCommandResult,
                    ],
                ),
            ],
        ];
    }

    private function mockCommandExecute(Command $command, CommandResult $commandResult): Command
    {
        $command = Mockery::mock($command);
        $command
            ->shouldReceive('execute')
            ->andReturn($commandResult);

        return $command;
    }

    /**
     * @param Command[] $commands
     * @param CommandResult[] $commandResults
     *
     * @return ClientHandler
     */
    private function createClientHandler(array $commands, array $commandResults): ClientHandler
    {
        $clientHandler = Mockery::mock(ClientHandler::class);

        $clientHandler
            ->shouldReceive('readCommand')
            ->andReturnUsing(function () use ($commands) {
                $command = $commands[$this->commandIndex];

                $this->commandIndex++;

                return $command;
            });

        $clientHandler
            ->shouldReceive('writeResponse')
            ->withArgs(function (CommandResult $commandResult) use ($commandResults) {
                self::assertSame($commandResults[$this->resultIndex], $commandResult);

                $this->resultIndex++;

                return true;
            });

        $clientHandler
            ->shouldReceive('stop');

        return $clientHandler;
    }

    private function createClientHandlerFactory(ClientHandler $clientHandler): ClientHandlerFactory
    {
        $clientHandlerFactory = Mockery::mock(ClientHandlerFactory::class);
        $clientHandlerFactory
            ->shouldReceive('create')
            ->andReturn($clientHandler);

        return $clientHandlerFactory;
    }
}
