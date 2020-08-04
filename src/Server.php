<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy;

use webignition\DockerTcpCliProxy\Model\CommunicationSocket;
use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Services\CommandReader;
use webignition\DockerTcpCliProxy\Services\ResponseWriter;

class Server
{
    private ListenSocket $listenSocket;

    public function __construct(ListenSocket $listenSocket)
    {
        $this->listenSocket = $listenSocket;
    }

    public function handleClients(): void
    {
        $communicationSocket = new CommunicationSocket($this->listenSocket);

        $commandReader = new CommandReader($communicationSocket);
        $responseWriter = new ResponseWriter($communicationSocket);

        do {
            $command = $commandReader->read();

            if ($command->isExecutable()) {
                $responseWriter->write($command->execute());
            }
        } while (false === $command->isCloseClientConnection());

        $communicationSocket->close();
    }

    public function stopListening(): void
    {
        $this->listenSocket->close();
    }
}
