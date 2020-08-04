<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use Socket\Raw\Socket;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;

class CommunicationSocketFactory
{
    private Socket $listenSocket;

    public function __construct(Socket $listenSocket)
    {
        $this->listenSocket = $listenSocket;
    }

    public function create(): CommunicationSocket
    {
        return new CommunicationSocket($this->listenSocket);
    }
}
