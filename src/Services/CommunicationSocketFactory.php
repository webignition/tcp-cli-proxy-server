<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use webignition\DockerTcpCliProxy\Model\CommunicationSocket;
use webignition\DockerTcpCliProxy\Model\ListenSocket;

class CommunicationSocketFactory
{
    private ListenSocket $listenSocket;

    public function __construct(ListenSocket $listenSocket)
    {
        $this->listenSocket = $listenSocket;
    }

    public function create(): CommunicationSocket
    {
        return new CommunicationSocket($this->listenSocket);
    }
}
