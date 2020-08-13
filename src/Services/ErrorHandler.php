<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

class ErrorHandler
{
    private ?array $lastError = null;

    public function start(): void
    {
        $this->lastError = null;

        set_error_handler(function (int $severity, string $errorMessage, ?string $file, ?int $line) {
            $this->lastError = [
                'severity' => $severity,
                'errorMessage' => $errorMessage,
                'file' => $file,
                'line' => $line,
            ];
        });
    }

    /**
     * @throws \ErrorException
     */
    public function stop(): void
    {
        restore_error_handler();

        if ($this->lastError !== null) {
            $exception = new \ErrorException(
                $this->lastError['errorMessage'],
                0,
                $this->lastError['severity'],
                $this->lastError['file'],
                $this->lastError['line']
            );

            if (false === $this->isExpected($exception)) {
                throw $exception;
            }
        }
    }

    private function isExpected(\ErrorException $errorException): bool
    {
        return false;
    }
}
