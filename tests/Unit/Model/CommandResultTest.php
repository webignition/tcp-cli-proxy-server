<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use webignition\DockerTcpCliProxy\Model\CommandResult;

class CommandResultTest extends TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(int $exitCode, string $response)
    {
        $commandResult = new CommandResult($exitCode, $response);

        self::assertSame($exitCode, $commandResult->getExitCode());
        self::assertSame($response, $commandResult->getResponse());
    }

    public function createDataProvider(): array
    {
        return [
            'success' => [
                'exitCode' => 0,
                'response' => 'success response content',
            ],
            'failure' => [
                'exitCode' => 1,
                'response' => 'failure response content',
            ],
        ];
    }
}
