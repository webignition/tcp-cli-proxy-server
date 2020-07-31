<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

use Socket\Raw\Socket;

abstract class AbstractSocket
{
    private ?Socket $socket = null;

    abstract protected function createSocket(): Socket;

    public function getSocket(): Socket
    {
        if (null === $this->socket) {
            $this->socket = $this->createSocket();
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
