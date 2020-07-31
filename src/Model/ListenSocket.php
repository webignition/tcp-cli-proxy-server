<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

use Socket\Raw\Factory;
use Socket\Raw\Socket;

class ListenSocket
{
    private string $connectionString;
    private ?Socket $socket = null;

    public function __construct(string $connectionString)
    {
        $this->connectionString = $connectionString;
    }

    public function getSocket(): Socket
    {
        if (null === $this->socket) {
            $this->socket = (new Factory())->createServer($this->connectionString);
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
