<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

class Command
{
    public const CLOSE_CLIENT_CONNECTION_COMMAND = 'quit';

    private string $content;

    public function __construct(string $content)
    {
        $this->content = trim($content);
    }

    public function isExecutable(): bool
    {
        return false === $this->isEmpty() && false === $this->isCloseClientConnection();
    }

    public function isCloseClientConnection(): bool
    {
        return self::CLOSE_CLIENT_CONNECTION_COMMAND === $this->content;
    }

    public function isEmpty(): bool
    {
        return '' === $this->content;
    }

    public function execute(): CommandResult
    {
        $output = [];
        $exitCode = null;
        exec($this->content, $output, $exitCode);

        return new CommandResult((int) $exitCode, implode("\n", $output));
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
