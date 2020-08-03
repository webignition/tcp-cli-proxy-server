<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy;

use Socket\Raw\Factory;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;
use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Services\CommandReader;
use webignition\DockerTcpCliProxy\Services\ResponseWriter;

class Server
{
    private string $bindAddress;
    private int $bindPort;

    private ListenSocket $listenSocket;

    public function __construct(string $bindAddress, int $bindPort)
    {
        $this->bindAddress = $bindAddress;
        $this->bindPort = $bindPort;
        $this->listenSocket = new ListenSocket(
            new Factory(),
            sprintf(
                'tcp://%s:%d',
                $this->bindAddress,
                $this->bindPort
            )
        );
    }

    public function handleClients(): void
    {
        while (true) {
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
    }

    public function stopListening(): void
    {
        $this->listenSocket->close();
    }
}
