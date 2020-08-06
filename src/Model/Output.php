<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Model;

class Output
{
    private int $exitCode;
    private string $content;

    public function __construct(int $exitCode, string $content)
    {
        $this->content = $content;
        $this->exitCode = $exitCode;
    }

    public static function fromString(string $serialisedOutput): self
    {
        $parts = explode("\n", $serialisedOutput, 2);

        return new Output(
            ((int) $parts[0]) ?? 0,
            $parts[1] ?? ''
        );
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isSuccessful(): bool
    {
        return 0 === $this->exitCode;
    }

    public function __toString()
    {
        return (string) $this->exitCode . "\n" . $this->content;
    }
}
