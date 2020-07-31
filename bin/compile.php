<?php

declare(strict_types=1);

$root = (string) realpath(__DIR__ . '/..');

require $root . '/vendor/autoload.php';

use webignition\DockerTcpCliProxy\Services\PharBuilder;

$binPath = __DIR__ . '/server';

$pharBuilder = new PharBuilder();
$pharBuilder->build($root, $binPath);
