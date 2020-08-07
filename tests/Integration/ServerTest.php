<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Tests\Integration;

use PHPUnit\Framework\TestCase;
use webignition\TcpCliProxyClient\Client;
use webignition\TcpCliProxyModels\Output;

class ServerTest extends TestCase
{
    /**
     * @dataProvider queryServerDataProvider
     */
    public function testQueryServer(string $remoteCommand, Output $expectedOutput)
    {
        $client = new Client('localhost', 8000);
        $output = $client->request($remoteCommand);

        self::assertEquals($expectedOutput, $output);
    }

    public function queryServerDataProvider(): array
    {
        return [
            'ls self' => [
                'remoteCommand' => 'ls ' . __FILE__,
                'expectedOutput' => new Output(0, __FILE__)
            ],
        ];
    }
}
