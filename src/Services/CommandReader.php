<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use webignition\DockerTcpCliProxy\Model\Command;
use webignition\DockerTcpCliProxy\Model\CommunicationSocket;

class CommandReader extends AbstractSocketHandler
{
    public function read(): Command
    {
        // @todo: handle exceptions in #14 (as a consequence of _read)
        $buffer = $this->communicationSocket->getSocket()->read(2048, PHP_NORMAL_READ);
        $buffer = trim($buffer);

        return new Command($buffer);
    }
}
