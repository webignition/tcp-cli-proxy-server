<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use webignition\TcpCliProxyModels\Output;

class ResponseWriter extends AbstractSocketHandler
{
    public function write(Output $output): void
    {
        $this->communicationSocket->getSocket()->send((string) $output, MSG_EOF);
    }
}
