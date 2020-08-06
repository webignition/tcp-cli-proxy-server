<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer;

use Socket\Raw\Factory;
use Socket\Raw\Socket;
use webignition\TcpCliProxyServer\Services\ClientHandlerFactory;
use webignition\TcpCliProxyServer\Services\CommunicationSocketFactory;
use webignition\TcpCliProxyServer\Services\ListenSocketFactory;

class Server
{
    private Socket $listenSocket;
    private ClientHandlerFactory $clientHandlerFactory;

    public function __construct(Socket $listenSocket, ClientHandlerFactory $clientHandlerFactory)
    {
        $this->listenSocket = $listenSocket;
        $this->clientHandlerFactory = $clientHandlerFactory;
    }

    public static function create(
        string $bindAddress,
        int $bindPort,
        ?Factory $socketFactory = null,
        ?ListenSocketFactory $listenSocketFactory = null
    ): self {
        $socketFactory = $socketFactory ?? new Factory();
        $listenSocketFactory = $listenSocketFactory ?? new ListenSocketFactory($socketFactory);
        $listenSocket = $listenSocketFactory->create($bindAddress, $bindPort);

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

        $command = $clientHandler->readCommand();

        if ($command->isExecutable()) {
            $clientHandler->writeResponse($command->execute());
        }

        $clientHandler->stop();
    }

    public function stopListening(): void
    {
        $this->listenSocket->shutdown();
        $this->listenSocket->close();
    }
}
