<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use webignition\TcpCliProxyServer\Model\CommunicationSocket;

abstract class AbstractSocketHandler
{
    protected CommunicationSocket $communicationSocket;

    public function __construct(CommunicationSocket $communicationSocket)
    {
        $this->communicationSocket = $communicationSocket;
    }
}
