<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    /**
     * @param string $command
     *
     * @return Process<\Generator>
     */
    public function create(string $command): Process
    {
        return Process::fromShellCommandline($command);
    }
}
