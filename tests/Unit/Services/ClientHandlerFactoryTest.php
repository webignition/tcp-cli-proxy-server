<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Services;

use Mockery;
use PHPUnit\Framework\TestCase;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;
use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Services\ClientHandler;
use webignition\DockerTcpCliProxy\Services\ClientHandlerFactory;
use webignition\DockerTcpCliProxy\Services\CommandReader;
use webignition\DockerTcpCliProxy\Services\CommunicationSocketFactory;
use webignition\DockerTcpCliProxy\Services\ResponseWriter;

class ClientHandlerFactoryTest extends TestCase
{
    public function testCreate()
    {
        $listenSocket = Mockery::mock(ListenSocket::class);
        $communicationSocketFactory = new CommunicationSocketFactory($listenSocket);

        $clientHandlerFactory = new ClientHandlerFactory($communicationSocketFactory);

        $clientHandler = $clientHandlerFactory->create();

        $expectedCommunicationSocket = new CommunicationSocket($listenSocket);

        self::assertEquals(
            new ClientHandler(
                $expectedCommunicationSocket,
                new CommandReader($expectedCommunicationSocket),
                new ResponseWriter($expectedCommunicationSocket)
            ),
            $clientHandler
        );
    }
}
