<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Model;

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
            $this->socket = $this->listenSocket->accept();
        }

        return $this->socket;
    }

    public function close(): void
    {
        if ($this->socket instanceof Socket) {
            $this->socket->shutdown();
            $this->socket->close();
        }
    }
}
