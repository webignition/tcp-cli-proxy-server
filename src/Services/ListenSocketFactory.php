<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use Socket\Raw\Factory;
use webignition\DockerTcpCliProxy\Model\ListenSocket;

class ListenSocketFactory
{
    public function create(string $bindAddress, int $bindPort): ListenSocket
    {
        return new ListenSocket(
            new Factory(),
            sprintf(
                'tcp://%s:%d',
                $bindAddress,
                $bindPort
            )
        );
    }
}
