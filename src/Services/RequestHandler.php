<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

class RequestHandler
{
    /**
     * @var resource
     */
    private $handle;
    private ProcessFactory $processFactory;

    /**
     * @param resource $handle
     * @param ProcessFactory $processFactory
     */
    public function __construct($handle, ProcessFactory $processFactory)
    {
        if (!is_resource($handle)) {
            throw new \TypeError('Provided handle is not a resource');
        }

        $this->handle = $handle;
        $this->processFactory = $processFactory;
    }

    public function handle(): int
    {
        $command = (string) fgets($this->handle);
        $process = $this->processFactory->create($command);

        $exitCode = $process->run(function ($type, $buffer) {
            fwrite($this->handle, $buffer);
        });

        fwrite($this->handle, "\n" . (string) $exitCode);

        return $exitCode;
    }
}
