<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy;

use webignition\DockerTcpCliProxy\Model\ListenSocket;
use webignition\DockerTcpCliProxy\Services\ClientHandlerFactory;
use webignition\DockerTcpCliProxy\Services\CommunicationSocketFactory;
use webignition\DockerTcpCliProxy\Services\ListenSocketFactory;

class Server
{
    private ListenSocket $listenSocket;
    private ClientHandlerFactory $clientHandlerFactory;

    public function __construct(ListenSocket $listenSocket, ClientHandlerFactory $clientHandlerFactory)
    {
        $this->listenSocket = $listenSocket;
        $this->clientHandlerFactory = $clientHandlerFactory;
    }

    public static function create(string $bindAddress, int $bindPort): self
    {
        $listenSocket = (new ListenSocketFactory())->create($bindAddress, $bindPort);

        return new Server(
            $listenSocket,
            new ClientHandlerFactory(
                new CommunicationSocketFactory($listenSocket)
            )
        );
    }

    public function handleClient(): void
    {
        $clientHandler = $this->clientHandlerFactory->create();

        do {
            $command = $clientHandler->readCommand();

            if ($command->isExecutable()) {
                $clientHandler->writeResponse($command->execute());
            }
        } while (false === $command->isCloseClientConnection());

        $clientHandler->stop();
    }

    public function stopListening(): void
    {
        $this->listenSocket->close();
    }
}
