<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use webignition\ObjectReflector\ObjectReflector;
use webignition\TcpCliProxyServer\Services\ErrorHandler;
use webignition\TcpCliProxyServer\Services\RequestHandler;
use webignition\TcpCliProxyServer\Services\SocketFactory;
use webignition\TcpCliProxyServer\StreamingServer;

class StreamingServerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testCreate()
    {
        $host = 'localhost';
        $port = 8000;

        /** @var resource $socket */
        $socket = Mockery::mock();

        $requestHandler = Mockery::mock(RequestHandler::class);
        $errorHandler = Mockery::mock(ErrorHandler::class);
        $errorHandler
            ->shouldReceive('start');

        $errorHandler
            ->shouldReceive('stop');

        $socketFactory = Mockery::mock(SocketFactory::class);
        $socketFactory
            ->shouldReceive('create')
            ->with($host, $port)
            ->andReturn($socket);

        $server = new StreamingServer($host, $port, $socketFactory, $requestHandler, $errorHandler);

        self::assertSame($errorHandler, ObjectReflector::getProperty($server, 'errorHandler'));
        self::assertSame($socket, ObjectReflector::getProperty($server, 'socket'));
        self::assertSame($requestHandler, ObjectReflector::getProperty($server, 'requestHandler'));
    }
}
