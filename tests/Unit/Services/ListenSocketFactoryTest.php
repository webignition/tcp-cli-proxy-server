<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Socket\Raw\Factory;
use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Services\ListenSocketFactory;

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
