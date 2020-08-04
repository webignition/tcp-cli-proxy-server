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
            'null command' => [
                'command' => new Command(null),
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
            'null command' => [
                'command' => new Command(null),
                'expectedIsCloseClientConnection' => false,
            ],
            'quit command' => [
                'command' => new Command(Command::CLOSE_CLIENT_CONNECTION_COMMAND),
                'expectedIsCloseClientConnection' => true,
            ],
        ];
    }

    /**
     * @dataProvider isNullDataProvider
     */
    public function testIsNull(Command $command, bool $expectedIsNull)
    {
        self::assertSame($expectedIsNull, $command->isNull());
    }

    public function isNullDataProvider(): array
    {
        return [
            'executable command' => [
                'command' => new Command('ls'),
                'expectedIsNull' => false,
            ],
            'null command' => [
                'command' => new Command(null),
                'expectedIsNull' => true,
            ],
            'quit command' => [
                'command' => new Command(Command::CLOSE_CLIENT_CONNECTION_COMMAND),
                'expectedIsNull' => false,
            ],
        ];
    }

    public function testToString()
    {
        $commandString = 'ls';

        self::assertSame($commandString, (string) new Command($commandString));
    }
}
