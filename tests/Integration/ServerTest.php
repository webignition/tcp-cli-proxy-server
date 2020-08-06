<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Integration;

use PHPUnit\Framework\TestCase;
use webignition\TcpCliProxyServer\Model\Output;

class ServerTest extends TestCase
{
    /**
     * @dataProvider queryServerDataProvider
     */
    public function testQueryServer(
        string $remoteCommand,
        int $expectedRemoteCommandExitCode,
        string $expectedResponse
    ) {
        $netcatCommand = '(echo "' . $remoteCommand . '"; sleep 1; echo "quit") | netcat localhost 8000';

        $rawOutput = [];
        $commandExitCode = null;
        exec($netcatCommand, $rawOutput, $commandExitCode);

        self::assertSame(0, $commandExitCode);
        self::assertGreaterThanOrEqual(1, count($rawOutput));

        $output = Output::fromString(implode("\n", $rawOutput));

        self::assertSame($expectedRemoteCommandExitCode, $output->getExitCode());
        self::assertSame($expectedResponse, $output->getContent());
    }

    public function queryServerDataProvider(): array
    {
        return [
            'ls self' => [
                'remoteCommand' => 'ls ' . __FILE__,
                'expectedRemoteCommandExitCode' => 0,
                'expectedResponse' => __FILE__,
            ],
        ];
    }
}
