<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use Socket\Raw\Socket;
use webignition\DockerTcpCliProxy\Model\Command;

class CommandReader
{
    private Socket $socket;

    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
    }

    public function read(): Command
    {
        // @todo: handle exceptions in #14 (as a consequence of _read)
        $buffer = $this->socket->read(2048, PHP_NORMAL_READ);
        $buffer = trim($buffer);

        return new Command($buffer);
    }
}
