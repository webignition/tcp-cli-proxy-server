<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use webignition\ErrorHandler\ErrorHandler;
use webignition\ObjectReflector\ObjectReflector;
use webignition\TcpCliProxyServer\Server;
use webignition\TcpCliProxyServer\Services\RequestHandler;
use webignition\TcpCliProxyServer\Services\SocketFactory;

class ServerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testCreate(): void
    {
        $host = 'localhost';
        $port = 8000;

        /** @var resource $socket */
        $socket = Mockery::mock();

        $requestHandler = Mockery::mock(RequestHandler::class);
        $errorHandler = Mockery::mock(ErrorHandler::class);
        $errorHandler
            ->shouldReceive('start')
        ;

        $errorHandler
            ->shouldReceive('stop')
        ;

        $socketFactory = Mockery::mock(SocketFactory::class);
        $socketFactory
            ->shouldReceive('create')
            ->with($host, $port)
            ->andReturn($socket)
        ;

        $server = new Server($host, $port, $errorHandler, $socketFactory, $requestHandler);

        self::assertSame($errorHandler, ObjectReflector::getProperty($server, 'errorHandler'));
        self::assertSame($socket, ObjectReflector::getProperty($server, 'socket'));
        self::assertSame($requestHandler, ObjectReflector::getProperty($server, 'requestHandler'));
    }
}
