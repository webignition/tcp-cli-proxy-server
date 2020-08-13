<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use phpmock\mockery\PHPMockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use webignition\TcpCliProxyServer\Services\ProcessFactory;
use webignition\TcpCliProxyServer\Services\RequestHandler;

class RequestHandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle(string $command, int $processExitCode)
    {
        /** @var resource $resource */
        $resource = Mockery::mock();

        PHPMockery::mock('webignition\TcpCliProxyServer\Services', 'is_resource')
            ->with($resource)
            ->andReturnTrue();

        PHPMockery::mock('webignition\TcpCliProxyServer\Services', 'fgets')
            ->with($resource)
            ->andReturn($command);

        PHPMockery::mock('webignition\TcpCliProxyServer\Services', 'fwrite')
            ->with($resource, "\n" . (string) $processExitCode);

        $process = Mockery::mock(Process::class);
        $process
            ->shouldReceive('run')
            ->withArgs(function () {
                return true;
            })
            ->andReturn($processExitCode);

        $processFactory = Mockery::mock(ProcessFactory::class);
        $processFactory
            ->shouldReceive('create')
            ->with($command)
            ->andReturn($process);

        $requestHandler = new RequestHandler($resource, $processFactory);
        $requestHandlerExitCode = $requestHandler->handle();

        self::assertSame($processExitCode, $requestHandlerExitCode);
    }

    public function handleDataProvider(): array
    {
        return [
            'exit code 0' => [
                'command' => 'command text',
                'processExitCode' => 0,
            ],
            'exit code 127' => [
                'command' => 'command text',
                'processExitCode' => 127,
            ],
        ];
    }

    public function testCreateHandleNotResource()
    {
        /** @var resource $resource */
        $resource = 'not a resource';

        self::expectException(\TypeError::class);
        self::expectExceptionMessage('Provided handle is not a resource');

        new RequestHandler($resource, new ProcessFactory());
    }
}
