<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Integration\Bin;

use PHPUnit\Framework\TestCase;

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

        $output = [];
        $commandExitCode = null;
        exec($netcatCommand, $output, $commandExitCode);

        self::assertSame(0, $commandExitCode);
        self::assertGreaterThanOrEqual(1, count($output));

        $remoteCommandExitCode = (int) $output[0];
        array_shift($output);

        $response = implode("\n", $output);

        self::assertSame($expectedRemoteCommandExitCode, $remoteCommandExitCode);
        self::assertSame($expectedResponse, $response);
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
