<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use webignition\TcpCliProxyServer\Services\ProcessFactory;

class ProcessFactoryTest extends TestCase
{
    public function testCreate()
    {
        $processFactory = new ProcessFactory();

        $command = 'command text';
        $process = $processFactory->create($command);

        self::assertInstanceOf(Process::class, $process);
        self::assertSame($command, $process->getCommandLine());
    }
}
