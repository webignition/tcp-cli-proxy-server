<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Functional\Model;

use PHPUnit\Framework\TestCase;
use webignition\TcpCliProxyServer\Model\Command;
use webignition\TcpCliProxyServer\Model\Output;

class CommandTest extends TestCase
{
    public function testExecute()
    {
        $command = new Command('ls ' . __FILE__);
        $expectedCommandResult = new Output(0, __FILE__);

        self::assertEquals($expectedCommandResult, $command->execute());
    }
}
