<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Socket;
use webignition\TcpCliProxyServer\Model\CommunicationSocket;
use webignition\TcpCliProxyServer\Services\ClientHandler;
use webignition\TcpCliProxyServer\Services\ClientHandlerFactory;
use webignition\TcpCliProxyServer\Services\CommandReader;
use webignition\TcpCliProxyServer\Services\CommunicationSocketFactory;
use webignition\TcpCliProxyServer\Services\ResponseWriter;

class ClientHandlerFactoryTest extends TestCase
{
    public function testCreate()
    {
        $listenSocket = Mockery::mock(Socket::class);
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
