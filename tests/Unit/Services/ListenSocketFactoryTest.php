<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Socket\Raw\Factory;
use Socket\Raw\Socket;
use webignition\TcpCliProxyServer\Services\ListenSocketFactory;

class ListenSocketFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testCreate()
    {
        $bindAddress = 'localhost';
        $bindPort = 8080;
        $expectedCreateServerAddress = 'tcp://' . $bindAddress . ':' . $bindPort;

        $socketFactory = Mockery::mock(Factory::class);
        $socketFactory
            ->shouldReceive('createServer')
            ->with($expectedCreateServerAddress)
            ->andReturn(Mockery::mock(Socket::class));

        $listenSocketFactory = new ListenSocketFactory($socketFactory);
        $listenSocketFactory->create($bindAddress, $bindPort);
    }
}
