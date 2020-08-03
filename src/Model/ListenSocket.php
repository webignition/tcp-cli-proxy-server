<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

use Socket\Raw\Factory;
use Socket\Raw\Socket;

class ListenSocket extends AbstractSocket
{
    private Factory $factory;
    private string $connectionString;

    public function __construct(Factory $factory, string $connectionString)
    {
        $this->factory = $factory;
        $this->connectionString = $connectionString;
    }

    protected function createSocket(): Socket
    {
        // @todo: handle exceptions in #14 (as a consequence of _create, _bind, _listen)
        return $this->factory->createServer($this->connectionString);
    }
}
