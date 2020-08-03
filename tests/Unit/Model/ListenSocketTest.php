<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Model;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Factory;
use Socket\Raw\Socket;
use webignition\DockerTcpCliProxy\Model\ListenSocket;

class ListenSocketTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testGetSocket()
    {
        $connectionString = 'mock connection string';
        $socket = Mockery::mock(Socket::class);

        $factory = $this->createFactory($connectionString, $socket);
        $listenSocket = new ListenSocket($factory, $connectionString);

        self::assertSame($socket, $listenSocket->getSocket());
    }

    public function testClose()
    {
        $connectionString = 'mock connection string';
        $socket = Mockery::mock(Socket::class);
        $socket
            ->shouldReceive('shutdown', 'close');

        $factory = $this->createFactory($connectionString, $socket);
        $listenSocket = new ListenSocket($factory, $connectionString);
        $listenSocket->getSocket();

        $listenSocket->close();
    }

    private function createFactory(string $connectionString, Socket $socket): Factory
    {
        $factory = Mockery::mock(Factory::class);
        $factory
            ->shouldReceive('createServer')
            ->with($connectionString)
            ->andReturn($socket);

        return $factory;
    }
}
