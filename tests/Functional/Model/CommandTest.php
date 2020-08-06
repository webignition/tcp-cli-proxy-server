<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Functional\Model;

use PHPUnit\Framework\TestCase;
use webignition\TcpCliProxyModels\Output;
use webignition\TcpCliProxyServer\Model\Command;

class CommandTest extends TestCase
{
    public function testExecute()
    {
        $command = new Command('ls ' . __FILE__);
        $expectedOutput = new Output(0, __FILE__);

        self::assertEquals($expectedOutput, $command->execute());
    }
}
