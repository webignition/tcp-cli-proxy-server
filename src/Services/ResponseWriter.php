<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use webignition\DockerTcpCliProxy\Model\CommandResult;

class ResponseWriter extends AbstractSocketHandler
{
    public function write(CommandResult $commandResult): void
    {
        $this->communicationSocket->getSocket()->write((string) $commandResult->getExitCode() . "\n");
        $this->communicationSocket->getSocket()->write($commandResult->getResponse() . "\n");
    }
}
