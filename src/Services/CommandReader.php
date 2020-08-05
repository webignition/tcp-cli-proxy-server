<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use webignition\DockerTcpCliProxy\Model\Command;

class CommandReader extends AbstractSocketHandler
{
    public function read(): Command
    {
        return new Command(
            $this->communicationSocket->getSocket()->read(2048, PHP_NORMAL_READ)
        );
    }
}
