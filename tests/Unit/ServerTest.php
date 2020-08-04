<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Factory;
use webignition\DockerTcpCliProxy\Model\Command;
use webignition\DockerTcpCliProxy\Model\CommandResult;
use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Server;
use webignition\DockerTcpCliProxy\Services\ClientHandler;
use webignition\DockerTcpCliProxy\Services\ClientHandlerFactory;
use webignition\DockerTcpCliProxy\Services\CommunicationSocketFactory;

class ServerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testCreate()
    {
        $bindAddress = 'localhost';
        $bindPort = 8080;

        $server = Server::create($bindAddress, $bindPort);

        $expectedListenSocket = new ListenSocket(
            new Factory(),
            'tcp://' . $bindAddress . ':' . $bindPort
        );

        self::assertEquals(
            new Server(
                $expectedListenSocket,
                new ClientHandlerFactory(
                    new CommunicationSocketFactory($expectedListenSocket)
                )
            ),
            $server
        );
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

    public function testStopListening()
    {
        $listenSocket = Mockery::mock(ListenSocket::class);
        $listenSocket
            ->shouldReceive('close')
            ->withNoArgs();

        $server = new Server(
            $listenSocket,
            Mockery::mock(ClientHandlerFactory::class)
        );

        $server->stopListening();
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
        $commandIndex = 0;
        $resultIndex = 0;

        $clientHandler = Mockery::mock(ClientHandler::class);

        $clientHandler
            ->shouldReceive('readCommand')
            ->andReturnUsing(function () use ($commands, &$commandIndex) {
                $command = $commands[$commandIndex];

                $commandIndex++;

                return $command;
            });

        $clientHandler
            ->shouldReceive('writeResponse')
            ->withArgs(function (CommandResult $commandResult) use ($commandResults, &$resultIndex) {
                self::assertSame($commandResults[$resultIndex], $commandResult);

                $resultIndex++;

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
