<?php

declare(strict_types=1);

namespace webignition\DockerTcpCliProxy;

use Socket\Raw\Factory;
use Socket\Raw\Socket;
use webignition\DockerTcpCliProxy\Model\CommandResult;

class Server
{
    private const CLOSE_CLIENT_CONNECTION_COMMAND = 'quit';

    private string $bindAddress;
    private int $bindPort;

    private Socket $listenSocket;
    private Socket $communicationSocket;

    public function __construct(string $bindAddress, int $bindPort)
    {
        $this->bindAddress = $bindAddress;
        $this->bindPort = $bindPort;
    }

    public function startListening(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _create_, _bind, _listen)
        $connectionString = sprintf(
            'tcp://%s:%d',
            $this->bindAddress,
            $this->bindPort
        );

        $this->listenSocket = (new Factory())->createServer($connectionString);
    }

    public function handleClients(): void
    {
        while (true) {
            $this->createCommunicationSocket();

            do {
                $command = $this->readCommand();
                $closeClientConnection = self::CLOSE_CLIENT_CONNECTION_COMMAND === $command;

                if (false === $closeClientConnection && is_string($command)) {
                    $this->returnCommandResult(
                        $this->createCommandResult($command)
                    );
                }
            } while (false === $closeClientConnection);

            $this->closeCommunicationSocket();
        }
    }

    public function stopListening(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _shutdown)
        $this->listenSocket->shutdown();
        $this->listenSocket->close();
    }

    private function createCommunicationSocket(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _accept)
        $this->communicationSocket = $this->listenSocket->accept();
    }

    private function closeCommunicationSocket(): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _shutdown)
        $this->communicationSocket->shutdown();
        $this->communicationSocket->close();
    }

    private function readCommand(): ?string
    {
        // @todo: handle exceptions in #14 (as a consequence of _read)
        $buffer = $this->communicationSocket->read(2048, PHP_NORMAL_READ);
        $buffer = trim($buffer);

        return '' === $buffer
            ? null
            : $buffer;
    }

    private function createCommandResult(string $command): CommandResult
    {
        $output = [];
        $exitCode = null;
        exec($command, $output, $exitCode);

        return new CommandResult((int) $exitCode, implode("\n", $output));
    }

    private function returnCommandResult(CommandResult $commandResult): void
    {
        // @todo: handle exceptions in #14 (as a consequence of _write)
        $this->communicationSocket->write((string) $commandResult->getExitCode() . "\n");
        // @todo: handle exceptions in #14 (as a consequence of _write)
        $this->communicationSocket->write($commandResult->getResponse() . "\n");
    }
}
