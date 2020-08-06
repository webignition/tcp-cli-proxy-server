<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

class ClientHandlerFactory
{
    private CommunicationSocketFactory $communicationSocketFactory;

    public function __construct(CommunicationSocketFactory $communicationSocketFactory)
    {
        $this->communicationSocketFactory = $communicationSocketFactory;
    }

    public function create(): ClientHandler
    {
        $communicationSocket = $this->communicationSocketFactory->create();

        return new ClientHandler(
            $communicationSocket,
            new CommandReader($communicationSocket),
            new ResponseWriter($communicationSocket)
        );
    }
}
