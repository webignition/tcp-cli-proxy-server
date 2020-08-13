<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer;

use webignition\TcpCliProxyServer\Exception\ServerCreationException;
use webignition\TcpCliProxyServer\Services\ErrorHandler;
use webignition\TcpCliProxyServer\Services\RequestHandler;
use webignition\TcpCliProxyServer\Services\SocketFactory;

class StreamingServer
{
    /**
     * @var resource
     */
    private $socket;
    private RequestHandler $requestHandler;
    private ErrorHandler $errorHandler;

    /**
     * @param string $host
     * @param int $port
     * @param SocketFactory $socketFactory
     * @param RequestHandler $requestHandler
     * @param ErrorHandler $errorHandler
     *
     * @throws ServerCreationException
     * @throws \ErrorException
     */
    public function __construct(
        string $host,
        int $port,
        SocketFactory $socketFactory,
        RequestHandler $requestHandler,
        ErrorHandler $errorHandler
    ) {
        $this->errorHandler = $errorHandler;
        $this->socket = $socketFactory->create($host, $port);
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param string $host
     * @param int $port
     *
     * @return self
     *
     * @throws ServerCreationException
     * @throws \ErrorException
     */
    public static function create(string $host, int $port): self
    {
        $errorHandler = new ErrorHandler();
        $socketFactory = new SocketFactory($errorHandler);

        return new StreamingServer($host, $port, $socketFactory, RequestHandler::createHandler(), $errorHandler);
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
