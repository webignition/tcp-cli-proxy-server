<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

class Logger
{
    private LoggerInterface $exceptionLogger;

    public function __construct()
    {
        $this->exceptionLogger = new MonologLogger('tcp-cli-proxy-stderr', [
            new StreamHandler('php://stderr', MonologLogger::DEBUG)
        ]);
    }

    public function logException(\Exception $exception): void
    {
        $context = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];

        if ($exception instanceof \ErrorException) {
            $context = array_merge(
                [
                    'severity' => $exception->getSeverity(),
                ],
                $context
            );
        }

        $this->exceptionLogger->error(get_class($exception) . ': ' . $exception->getMessage(), $context);
    }
}
