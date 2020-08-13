<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

class ErrorExcluder
{
    public function isExcluded(\ErrorException $errorException): bool
    {
        $exceptionMessage = $errorException->getMessage();

        if ($this->isStreamSocketAcceptSystemCallException($exceptionMessage)) {
            return true;
        }

        if ($this->isFWriteBrokenPipeException($exceptionMessage)) {
            return true;
        }

        return false;
    }

    private function isStreamSocketAcceptSystemCallException(string $message): bool
    {
        return preg_match('/^stream_socket_accept\(\):.*Interrupted system call$/', $message) > 0;
    }

    private function isFWriteBrokenPipeException(string $message): bool
    {
        return preg_match('/^fwrite\(\):.*Broken pipe$/', $message) > 0;
    }
}
