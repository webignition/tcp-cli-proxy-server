<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

class RequestHandler
{
    public function __construct(
        private ProcessFactory $processFactory
    ) {
        $this->processFactory = $processFactory;
    }

    public static function createHandler(): self
    {
        return new RequestHandler(
            new ProcessFactory()
        );
    }

    /**
     * @param resource $handle
     *
     * @throws \TypeError
     */
    public function handle($handle): int
    {
        if (!is_resource($handle)) {
            throw new \TypeError('Provided handle is not a resource');
        }

        $command = (string) fgets($handle);
        $process = $this->processFactory->create($command);

        $exitCode = $process->run(function ($type, $buffer) use ($handle) {
            fwrite($handle, $buffer);
        });

        if (is_resource($handle)) {
            fwrite($handle, "\n" . (string) $exitCode);
        }

        return $exitCode;
    }
}
