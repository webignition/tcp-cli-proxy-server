<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer;

use webignition\ErrorHandler\ErrorHandler;
use webignition\TcpCliProxyServer\Exception\ServerCreationException;
use webignition\TcpCliProxyServer\Services\RequestHandler;
use webignition\TcpCliProxyServer\Services\SocketFactory;

class Server
{
    /**
     * @var resource
     */
    private $socket;
    private RequestHandler $requestHandler;
    private ErrorHandler $errorHandler;

    /**
     * @throws ServerCreationException
     * @throws \ErrorException
     */
    public function __construct(
        string $host,
        int $port,
        ErrorHandler $errorHandler,
        SocketFactory $socketFactory,
        RequestHandler $requestHandler
    ) {
        $this->errorHandler = $errorHandler;
        $this->socket = $socketFactory->create($host, $port);
        $this->requestHandler = $requestHandler;
    }

    /**
     * @throws \ErrorException
     */
    public function run(): void
    {
        while (is_resource($this->socket)) {
            $this->errorHandler->start();
            $connection = stream_socket_accept($this->socket, -1);

            if (is_resource($connection)) {
                $this->requestHandler->handle($connection);

                fclose($connection);
            }

            $this->errorHandler->stop();
        }
    }

    public function stop(): void
    {
        fclose($this->socket);
    }
}
