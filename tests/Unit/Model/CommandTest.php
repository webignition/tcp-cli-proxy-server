<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\TcpCliProxyServer\Model\Command;

class CommandTest extends TestCase
{
    /**
     * @dataProvider isExecutableDataProvider
     */
    public function testIsExecutable(Command $command, bool $expectedIsExecutable)
    {
        self::assertSame($expectedIsExecutable, $command->isExecutable());
    }

    public function isExecutableDataProvider(): array
    {
        return [
            'executable command' => [
                'command' => new Command('ls'),
                'expectedIsExecutable' => true,
            ],
            'empty command' => [
                'command' => new Command(''),
                'expectedIsExecutable' => false,
            ],
        ];
    }

    /**
     * @dataProvider isEmptyDataProvider
     */
    public function testIsEmpty(Command $command, bool $expectedIsEmpty)
    {
        self::assertSame($expectedIsEmpty, $command->isEmpty());
    }

    public function isEmptyDataProvider(): array
    {
        return [
            'executable command' => [
                'command' => new Command('ls'),
                'expectedIsEmpty' => false,
            ],
            'empty command' => [
                'command' => new Command(''),
                'expectedIsEmpty' => true,
            ],
            'whitespace command' => [
                'command' => new Command('  '),
                'expectedIsEmpty' => true,
            ],
        ];
    }

    public function testToString()
    {
        $commandString = 'ls';

        self::assertSame($commandString, (string) new Command($commandString));
    }
}
