<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use webignition\ErrorHandler\ErrorHandler;
use webignition\TcpCliProxyServer\Exception\ServerCreationException;

class SocketFactory
{
    private ErrorHandler $errorHandler;
    private ?int $errorNumber = null;
    private ?string $errorMessage = null;

    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @throws \ErrorException
     * @throws ServerCreationException
     *
     * @return resource
     */
    public function create(string $host, int $port)
    {
        $this->errorHandler->start();
        $socket = stream_socket_server(
            sprintf('tcp://%s:%d', $host, $port),
            $this->errorNumber,
            $this->errorMessage
        );
        $this->errorHandler->stop();

        if (!is_resource($socket)) {
            throw new ServerCreationException((string) $this->errorMessage, (int) $this->errorNumber);
        }

        return $socket;
    }
}
