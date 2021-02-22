<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Integration;

use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    private const HOST = 'localhost';
    private const PORT = 8000;

    /**
     * @dataProvider queryServerDataProvider
     */
    public function testQueryServer(
        string $remoteCommand,
        int $expectedRemoteCommandExitCode,
        string $expectedResponse
    ): void {
        $netcatCommand = '(echo "' . $remoteCommand . '") | netcat ' . self::HOST . ' ' . self::PORT;

        $rawOutput = [];
        $commandExitCode = null;
        exec($netcatCommand, $rawOutput, $commandExitCode);

        self::assertSame(0, $commandExitCode);
        self::assertGreaterThanOrEqual(1, count($rawOutput));

        $reversedRawOutput = array_reverse($rawOutput);

        $remoteCommandExitCode = (int) array_shift($reversedRawOutput);
        $remoteCommandResponse = implode(array_reverse($reversedRawOutput));

        self::assertSame($expectedRemoteCommandExitCode, $remoteCommandExitCode);
        self::assertSame($expectedResponse, $remoteCommandResponse);
    }

    /**
     * @return array[]
     */
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

    public function testServerStreamsResponse(): void
    {
        $clientSocket = stream_socket_client('tcp://' . self::HOST . ':' . self::PORT);

        if (is_resource($clientSocket)) {
            fwrite($clientSocket, './tests/Integration/fixture.sh' . "\n");

            $before = microtime(true);
            $lineRetrievalDelays = [];
            $lines = [];

            while (!feof($clientSocket)) {
                $lines[] = fgets($clientSocket);
                $lineRetrievalDelays[] = microtime(true) - $before;
                $before = microtime(true);
            }
            fclose($clientSocket);

            self::assertSame(
                "line1\nline2\nline3\n\n0",
                implode('', $lines)
            );

            $echoLineDelays = array_slice($lineRetrievalDelays, 0, 3);
            foreach ($echoLineDelays as $delay) {
                self::assertGreaterThanOrEqual(0.1, $delay);
                self::assertLessThanOrEqual(0.11, $delay);
            }
        } else {
            $this->fail('Client connection to server failed');
        }
    }
}
