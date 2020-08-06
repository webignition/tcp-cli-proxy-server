<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Socket;
use webignition\TcpCliProxyModels\Output;
use webignition\TcpCliProxyServer\Model\CommunicationSocket;
use webignition\TcpCliProxyServer\Services\ResponseWriter;

class ResponseWriterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testWrite()
    {
        $outputExitCode = 0;
        $outputResponse = 'content';

        $output = new Output($outputExitCode, $outputResponse);

        $socket = Mockery::mock(Socket::class);
        $socket
            ->shouldReceive('send')
            ->with(((string) $output), MSG_EOF);

        $communicationSocket = Mockery::mock(CommunicationSocket::class);
        $communicationSocket
            ->shouldReceive('getSocket')
            ->andReturn($socket);

        $responseWriter = new ResponseWriter($communicationSocket);
        $responseWriter->write($output);
    }
}
