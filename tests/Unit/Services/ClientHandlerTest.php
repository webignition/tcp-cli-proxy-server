<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use webignition\DockerTcpCliProxy\Model\Command;
use webignition\DockerTcpCliProxy\Model\CommandResult;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;
use webignition\DockerTcpCliProxy\Services\ClientHandler;
use webignition\DockerTcpCliProxy\Services\CommandReader;
use webignition\DockerTcpCliProxy\Services\ResponseWriter;

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
        $commandResult = new CommandResult(0, '.');

        $responseWriter = Mockery::mock(ResponseWriter::class);
        $responseWriter
            ->shouldReceive('write')
            ->with($commandResult);

        $clientHandler = new ClientHandler(
            Mockery::mock(CommunicationSocket::class),
            Mockery::mock(CommandReader::class),
            $responseWriter
        );

        $clientHandler->writeResponse($commandResult);
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
