<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

class ErrorHandler
{
    private ErrorExcluder $excluder;
    private Logger $logger;

    public function __construct(ErrorExcluder $excluder, Logger $logger)
    {
        $this->excluder = $excluder;
        $this->logger = $logger;
    }

    public static function createHandler(): self
    {
        return new ErrorHandler(
            new ErrorExcluder(),
            new Logger()
        );
    }

    /**
     * @var array<mixed>
     */
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

            if (false === $this->excluder->isExcluded($exception)) {
                throw $exception;
            }

            $this->logger->logException($exception);
        }
    }
}
