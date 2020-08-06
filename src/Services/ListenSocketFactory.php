<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

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
        return $this->socketFactory->createServer(sprintf(
            'tcp://%s:%d',
            $bindAddress,
            $bindPort
        ));
    }
}
