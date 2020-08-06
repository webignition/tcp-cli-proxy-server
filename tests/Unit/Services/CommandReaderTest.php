<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Socket;
use webignition\TcpCliProxyServer\Model\Command;
use webignition\TcpCliProxyServer\Model\CommunicationSocket;
use webignition\TcpCliProxyServer\Services\CommandReader;

class CommandReaderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @dataProvider readDataProvider
     */
    public function testRead(CommunicationSocket $communicationSocket, Command $expectedCommand)
    {
        $commandReader = new CommandReader($communicationSocket);

        self::assertEquals($expectedCommand, $commandReader->read());
    }

    public function readDataProvider(): array
    {
        return [
            'command is read from socket' => [
                'communicationSocket' => $this->createCommunicationSocket(
                    $this->createRawSocket('command --content'),
                ),
                'expectedCommand' => new Command('command --content'),
            ],
            'command read from socket is trimmed' => [
                'communicationSocket' => $this->createCommunicationSocket(
                    $this->createRawSocket(' command-is-trimmed  '),
                ),
                'expectedCommand' => new Command('command-is-trimmed'),
            ],
        ];
    }

    private function createCommunicationSocket(Socket $rawSocket): CommunicationSocket
    {
        $communicationSocket = Mockery::mock(CommunicationSocket::class);
        $communicationSocket
            ->shouldReceive('getSocket')
            ->andReturn($rawSocket);

        return $communicationSocket;
    }

    private function createRawSocket(string $return): Socket
    {
        $socket = Mockery::mock(Socket::class);
        $socket
            ->shouldReceive('read')
            ->with(2048, PHP_BINARY_READ)
            ->andReturn($return);

        return $socket;
    }
}
