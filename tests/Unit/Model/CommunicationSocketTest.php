<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Model;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Socket;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;

class CommunicationSocketTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testGetSocket()
    {
        $communicationSocketRawSocket = Mockery::mock(Socket::class);
        $listenSocket = $this->createListenSocket($communicationSocketRawSocket);
        $communicationSocket = new CommunicationSocket($listenSocket);

        self::assertSame($communicationSocketRawSocket, $communicationSocket->getSocket());
    }

    public function testClose()
    {
        $communicationSocketRawSocket = Mockery::mock(Socket::class);
        $communicationSocketRawSocket
            ->shouldReceive('shutdown', 'close');

        $listenSocket = $this->createListenSocket($communicationSocketRawSocket);
        $communicationSocket = new CommunicationSocket($listenSocket);
        $communicationSocket->getSocket();

        $communicationSocket->close();
    }

    private function createListenSocket(Socket $communicationSocketRawSocket): Socket
    {
        $listenSocket = Mockery::mock(Socket::class);
        $listenSocket
            ->shouldReceive('accept')
            ->andReturn($communicationSocketRawSocket);

        return $listenSocket;
    }
}
