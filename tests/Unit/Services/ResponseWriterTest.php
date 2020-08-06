<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Socket;
use webignition\TcpCliProxyServer\Model\CommunicationSocket;
use webignition\TcpCliProxyServer\Model\Output;
use webignition\TcpCliProxyServer\Services\ResponseWriter;

class ResponseWriterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testWrite()
    {
        $commandResultExitCode = 0;
        $commandResultResponse = 'content';

        $commandResult = new Output($commandResultExitCode, $commandResultResponse);

        $socket = Mockery::mock(Socket::class);
        $socket
            ->shouldReceive('write')
            ->with(((string) $commandResult) . "\n");

        $communicationSocket = Mockery::mock(CommunicationSocket::class);
        $communicationSocket
            ->shouldReceive('getSocket')
            ->andReturn($socket);

        $responseWriter = new ResponseWriter($communicationSocket);
        $responseWriter->write($commandResult);
    }
}
