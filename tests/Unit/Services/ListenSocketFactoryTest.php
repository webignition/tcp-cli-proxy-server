<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Services;

use Mockery;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Factory;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;
use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Services\ClientHandler;
use webignition\DockerTcpCliProxy\Services\ClientHandlerFactory;
use webignition\DockerTcpCliProxy\Services\CommandReader;
use webignition\DockerTcpCliProxy\Services\CommunicationSocketFactory;
use webignition\DockerTcpCliProxy\Services\ListenSocketFactory;
use webignition\DockerTcpCliProxy\Services\ResponseWriter;

class ListenSocketFactoryTest extends TestCase
{
    public function testCreate()
    {
        $listenSocketFactory = new ListenSocketFactory();

        $bindAddress = 'localhost';
        $bindPort = 8080;

        $listenSocket = $listenSocketFactory->create($bindAddress, $bindPort);

        self::assertEquals(
            new ListenSocket(new Factory(), 'tcp://' . $bindAddress . ':' . $bindPort),
            $listenSocket
        );
    }
}
