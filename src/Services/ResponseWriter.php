<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use webignition\DockerTcpCliProxy\Model\CommandResult;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;

class ResponseWriter
{
    private CommunicationSocket $communicationSocket;

    public function __construct(CommunicationSocket $communicationSocket)
    {
        $this->communicationSocket = $communicationSocket;
    }

    public function write(CommandResult $commandResult): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _write)
        $this->communicationSocket->getSocket()->write((string) $commandResult->getExitCode() . "\n");
        // @todo: handle exceptions in #14 (as a consequence of _write)
        $this->communicationSocket->getSocket()->write($commandResult->getResponse() . "\n");
    }
}
