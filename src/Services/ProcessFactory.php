<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    /**
     * @return Process<\Generator>
     */
    public function create(string $command): Process
    {
        return Process::fromShellCommandline($command);
    }
}
