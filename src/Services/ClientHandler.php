<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use webignition\DockerTcpCliProxy\Model\Command;
use webignition\DockerTcpCliProxy\Model\CommandResult;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;

class ClientHandler
{
    private CommunicationSocket $communicationSocket;
    private CommandReader $commandReader;
    private ResponseWriter $responseWriter;

    public function __construct(
        CommunicationSocket $communicationSocket,
        CommandReader $commandReader,
        ResponseWriter $responseWriter
    ) {
        $this->communicationSocket = $communicationSocket;
        $this->commandReader = $commandReader;
        $this->responseWriter = $responseWriter;
    }

    public function readCommand(): Command
    {
        return $this->commandReader->read();
    }

    public function writeResponse(CommandResult $commandResult): void
    {
        $this->responseWriter->write($commandResult);
    }

    public function stop(): void
    {
        $this->communicationSocket->close();
    }
}
