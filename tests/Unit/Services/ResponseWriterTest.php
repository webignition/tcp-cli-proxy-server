<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Socket;
use webignition\TcpCliProxyServer\Model\CommandResult;
use webignition\TcpCliProxyServer\Model\CommunicationSocket;
use webignition\TcpCliProxyServer\Services\ResponseWriter;

class ResponseWriterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testWrite()
    {
        $commandResultExitCode = 0;
        $commandResultResponse = 'content';

        $socket = Mockery::mock(Socket::class);
        $socket
            ->shouldReceive('write')
            ->with((int) $commandResultExitCode . "\n");

        $socket
            ->shouldReceive('write')
            ->with($commandResultResponse . "\n");

        $communicationSocket = \Mockery::mock(CommunicationSocket::class);
        $communicationSocket
            ->shouldReceive('getSocket')
            ->andReturn($socket);

        $commandResult = new CommandResult($commandResultExitCode, $commandResultResponse);
        $responseWriter = new ResponseWriter($communicationSocket);

        $responseWriter->write($commandResult);
    }
}
