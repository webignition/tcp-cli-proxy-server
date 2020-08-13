<?php

declare(strict_types=1);

namespace webignition\TcpCliProxyServer\Exception;

class ServerCreationException extends \Exception
{
    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
    }
}
