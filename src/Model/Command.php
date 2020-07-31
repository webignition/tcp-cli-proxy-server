<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy\Model;

class Command
{
    private const CLOSE_CLIENT_CONNECTION_COMMAND = 'quit';

    private ?string $content;

    public function __construct(?string $content)
    {
        $this->content = $content;
    }

    public function isExecutable(): bool
    {
        return false === $this->isNull() && false === $this->isCloseClientConnection();
    }

    public function isCloseClientConnection(): bool
    {
        return self::CLOSE_CLIENT_CONNECTION_COMMAND === $this->content;
    }

    public function isNull(): bool
    {
        return null === $this->content;
    }

    public function __toString(): string
    {
        return (string) $this->content;
    }
}
