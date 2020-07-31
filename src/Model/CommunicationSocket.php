<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

use Socket\Raw\Socket;

class CommunicationSocket extends AbstractSocket
{
    private ListenSocket $listenSocket;

    public function __construct(ListenSocket $listenSocket)
    {
        $this->listenSocket = $listenSocket;
    }

    protected function createSocket(): Socket
    {
        // @todo: handle exceptions in #14 (as a consequence of _accept)
        return $this->listenSocket->getSocket()->accept();
    }
}
