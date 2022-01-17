<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use phpmock\mockery\PHPMockery;
use PHPUnit\Framework\TestCase;
use webignition\ErrorHandler\ErrorHandler;
use webignition\ObjectReflector\ObjectReflector;
use webignition\TcpCliProxyServer\Exception\ServerCreationException;
use webignition\TcpCliProxyServer\Services\SocketFactory;

class SocketFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testCreateSuccess(): void
    {
        $host = 'localhost';
        $port = 8000;

        /** @var resource $socket */
        $socket = \Mockery::mock();

        PHPMockery::mock('webignition\TcpCliProxyServer\Services', 'is_resource')
            ->with($socket)
            ->andReturnTrue()
        ;

        PHPMockery::mock('webignition\TcpCliProxyServer\Services', 'stream_socket_server')
            ->withArgs(function (string $connectionString, $errorNumber, $errorMessage) use ($host, $port) {
                self::assertSame('tcp://' . $host . ':' . $port, $connectionString);
                self::assertNull($errorNumber);
                self::assertNull($errorMessage);

                return true;
            })
            ->andReturn($socket)
        ;

        $errorHandler = \Mockery::mock(ErrorHandler::class);
        $errorHandler
            ->shouldReceive('start')
        ;

        $errorHandler
            ->shouldReceive('stop')
        ;

        $factory = new SocketFactory($errorHandler);

        $createdSocket = $factory->create($host, $port);

        self::assertSame($createdSocket, $socket);
    }

    public function testCreateFailureStreamSocketServerReturnsFalse(): void
    {
        $host = 'localhost';
        $port = 8000;

        $errorHandler = \Mockery::mock(ErrorHandler::class);
        $errorHandler
            ->shouldReceive('start')
        ;

        $errorHandler
            ->shouldReceive('stop')
        ;

        $factory = new SocketFactory($errorHandler);

        $errorMessage = 'server creation error message';
        $errorNumber = 123;

        PHPMockery::mock('webignition\TcpCliProxyServer\Services', 'is_resource')
            ->with(false)
            ->andReturnFalse()
        ;

        PHPMockery::mock('webignition\TcpCliProxyServer\Services', 'stream_socket_server')
            ->andReturnUsing(function () use ($factory, $errorMessage, $errorNumber) {
                ObjectReflector::setProperty($factory, SocketFactory::class, 'errorMessage', $errorMessage);
                ObjectReflector::setProperty($factory, SocketFactory::class, 'errorNumber', $errorNumber);

                return false;
            })
        ;

        self::expectExceptionObject(new ServerCreationException($errorMessage, $errorNumber));
        $factory->create($host, $port);
    }
}
