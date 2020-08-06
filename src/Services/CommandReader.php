<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use webignition\TcpCliProxyServer\Model\Command;

class CommandReader extends AbstractSocketHandler
{
    public function read(): Command
    {
        return new Command(
            $this->communicationSocket->getSocket()->read(2048, PHP_NORMAL_READ)
        );
    }
}
