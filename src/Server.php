<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy;

use Socket\Raw\Factory;
use Socket\Raw\Socket;
use webignition\DockerTcpCliProxy\Services\CommandReader;
use webignition\DockerTcpCliProxy\Services\ResponseWriter;

class Server
{
    private string $bindAddress;
    private int $bindPort;

    private Socket $listenSocket;
    private Socket $communicationSocket;

    public function __construct(string $bindAddress, int $bindPort)
    {
        $this->bindAddress = $bindAddress;
        $this->bindPort = $bindPort;
    }

    public function startListening(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _create_, _bind, _listen)
        $connectionString = sprintf(
            'tcp://%s:%d',
            $this->bindAddress,
            $this->bindPort
        );

        $this->listenSocket = (new Factory())->createServer($connectionString);
    }

    public function handleClients(): void
    {
        while (true) {
            $this->createCommunicationSocket();
            $commandReader = new CommandReader($this->communicationSocket);
            $responseWriter = new ResponseWriter($this->communicationSocket);

            do {
                $command = $commandReader->read();

                if ($command->isExecutable()) {
                    $responseWriter->write($command->execute());
                }
            } while (false === $command->isCloseClientConnection());

            $this->closeCommunicationSocket();
        }
    }

    public function stopListening(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _shutdown)
        $this->listenSocket->shutdown();
        $this->listenSocket->close();
    }

    private function createCommunicationSocket(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _accept)
        $this->communicationSocket = $this->listenSocket->accept();
    }

    private function closeCommunicationSocket(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _shutdown)
        $this->communicationSocket->shutdown();
        $this->communicationSocket->close();
    }
}
