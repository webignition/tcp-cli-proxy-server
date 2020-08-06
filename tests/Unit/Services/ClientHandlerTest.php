<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use webignition\TcpCliProxyServer\Model\Command;
use webignition\TcpCliProxyServer\Model\CommunicationSocket;
use webignition\TcpCliProxyServer\Model\Output;
use webignition\TcpCliProxyServer\Services\ClientHandler;
use webignition\TcpCliProxyServer\Services\CommandReader;
use webignition\TcpCliProxyServer\Services\ResponseWriter;

class ClientHandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testReadCommand()
    {
        $commandReader = Mockery::mock(CommandReader::class);
        $commandReader
            ->shouldReceive('read')
            ->withNoArgs()
            ->andReturn(Mockery::mock(Command::class));

        $clientHandler = new ClientHandler(
            Mockery::mock(CommunicationSocket::class),
            $commandReader,
            Mockery::mock(ResponseWriter::class)
        );

        $clientHandler->readCommand();
    }

    public function testWriteResponse()
    {
        $output = new Output(0, '.');

        $responseWriter = Mockery::mock(ResponseWriter::class);
        $responseWriter
            ->shouldReceive('write')
            ->with($output);

        $clientHandler = new ClientHandler(
            Mockery::mock(CommunicationSocket::class),
            Mockery::mock(CommandReader::class),
            $responseWriter
        );

        $clientHandler->writeResponse($output);
    }

    public function testStop()
    {
        $communicationSocket = Mockery::mock(CommunicationSocket::class);
        $communicationSocket
            ->shouldReceive('close')
            ->withNoArgs();

        $clientHandler = new ClientHandler(
            $communicationSocket,
            Mockery::mock(CommandReader::class),
            Mockery::mock(ResponseWriter::class)
        );

        $clientHandler->stop();
    }
}
