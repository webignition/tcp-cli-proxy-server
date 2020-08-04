<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

use Socket\Raw\Socket;

class CommunicationSocket
{
    private Socket $listenSocket;
    private ?Socket $socket = null;

    public function __construct(Socket $listenSocket)
    {
        $this->listenSocket = $listenSocket;
    }

    public function getSocket(): Socket
    {
        if (null === $this->socket) {
            // @todo: handle exceptions in #14 (as a consequence of _accept)
            $this->socket = $this->listenSocket->accept();
        }

        return $this->socket;
    }

    public function close(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _shutdown)
        if ($this->socket instanceof Socket) {
            $this->socket->shutdown();
            $this->socket->close();
        }
    }
}
