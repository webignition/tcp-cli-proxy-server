<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

class ErrorHandler
{
    private ExceptionExaminer $exceptionExaminer;
    private Logger $logger;

    public function __construct(ExceptionExaminer $exceptionExaminer, Logger $logger)
    {
        $this->exceptionExaminer = $exceptionExaminer;
        $this->logger = $logger;
    }

    public static function createHandler(): self
    {
        return new ErrorHandler(
            new ExceptionExaminer(),
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

            if ($this->exceptionExaminer->isFatal($exception)) {
                throw $exception;
            }

            if (false === $this->exceptionExaminer->isExpected($exception)) {
                $this->logger->logException($exception);
            }
        }
    }
}
