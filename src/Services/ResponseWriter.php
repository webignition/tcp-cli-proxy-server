<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use Socket\Raw\Socket;
use webignition\DockerTcpCliProxy\Model\CommandResult;

class ResponseWriter
{
    private Socket $socket;

    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
    }

    public function write(CommandResult $commandResult): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _write)
        $this->socket->write((string) $commandResult->getExitCode() . "\n");
        // @todo: handle exceptions in #14 (as a consequence of _write)
        $this->socket->write($commandResult->getResponse() . "\n");
    }
}
