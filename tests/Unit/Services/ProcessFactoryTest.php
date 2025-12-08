<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use webignition\TcpCliProxyServer\Services\ProcessFactory;

class ProcessFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $processFactory = new ProcessFactory();

        $command = 'command text';
        $process = $processFactory->create($command);

        self::assertSame($command, $process->getCommandLine());
    }
}
