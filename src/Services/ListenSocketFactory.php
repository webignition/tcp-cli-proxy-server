<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Services;

use Socket\Raw\Factory;
use Socket\Raw\Socket;

class ListenSocketFactory
{
    private Factory $socketFactory;

    public function __construct(Factory $socketFactory)
    {
        $this->socketFactory = $socketFactory;
    }

    public function create(string $bindAddress, int $bindPort): Socket
    {
        // @todo: handle exceptions in #14 (as a consequence of _create, _bind, _listen)
        return $this->socketFactory->createServer(sprintf(
            'tcp://%s:%d',
            $bindAddress,
            $bindPort
        ));
    }
}
