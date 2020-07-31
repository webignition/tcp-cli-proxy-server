<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use webignition\DockerTcpCliProxy\Model\CommunicationSocket;

abstract class AbstractSocketHandler
{
    protected CommunicationSocket $communicationSocket;

    public function __construct(CommunicationSocket $communicationSocket)
    {
        $this->communicationSocket = $communicationSocket;
    }
}
