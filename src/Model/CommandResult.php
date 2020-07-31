<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

class CommandResult
{
    private int $exitCode;
    private string $response;

    public function __construct(int $exitCode, string $response)
    {
        $this->exitCode = $exitCode;
        $this->response = $response;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function getResponse(): string
    {
        return $this->response;
    }
}
