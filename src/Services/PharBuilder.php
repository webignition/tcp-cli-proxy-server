<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Services;

use webignition\SingleCommandApplicationPharBuilder\Builder;

class PharBuilder
{
    public function build(string $root, string $binPath): void
    {
        $latestGitTag = trim((string) (shell_exec('git describe --abbrev=0 --tags') ?? 'dev-master'));

        $this->setBinVersion($binPath, $latestGitTag);
        $this->executeBuild($root);
        $this->revertBinRunnerChanges($binPath);
    }

    private function setBinVersion(string $binPath, string $version): void
    {
        $binContent = file_get_contents($binPath);

        if (is_string($binContent)) {
            $binContent = str_replace(
                'const VERSION = \'dev-master\';',
                'const VERSION = \'' . $version . '\';',
                $binContent
            );

            file_put_contents($binPath, $binContent);
        }
    }

    private function executeBuild(string $root): void
    {
        (new Builder(
            $root,
            'build/server.phar',
            'bin/server',
            [
                'src',
                'vendor',
            ]
        ))->build();
    }

    private function revertBinRunnerChanges(string $binPath): void
    {
        shell_exec('git checkout -- ' . $binPath);
    }
}
