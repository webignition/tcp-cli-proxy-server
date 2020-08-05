<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\DockerTcpCliProxy\Model\Command;

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
            'quit command' => [
                'command' => new Command(Command::CLOSE_CLIENT_CONNECTION_COMMAND),
                'expectedIsExecutable' => false,
            ],
        ];
    }

    /**
     * @dataProvider isCloseClientConnectionDataProvider
     */
    public function testIsCloseClientConnection(Command $command, bool $expectedIsCloseClientConnection)
    {
        self::assertSame($expectedIsCloseClientConnection, $command->isCloseClientConnection());
    }

    public function isCloseClientConnectionDataProvider(): array
    {
        return [
            'executable command' => [
                'command' => new Command('ls'),
                'expectedIsCloseClientConnection' => false,
            ],
            'empty command' => [
                'command' => new Command(''),
                'expectedIsCloseClientConnection' => false,
            ],
            'quit command' => [
                'command' => new Command(Command::CLOSE_CLIENT_CONNECTION_COMMAND),
                'expectedIsCloseClientConnection' => true,
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
            'quit command' => [
                'command' => new Command(Command::CLOSE_CLIENT_CONNECTION_COMMAND),
                'expectedIsEmpty' => false,
            ],
        ];
    }

    public function testToString()
    {
        $commandString = 'ls';

        self::assertSame($commandString, (string) new Command($commandString));
    }
}
