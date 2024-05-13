<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

use Danilocgsilva\ConfigurationSpitter\DockerFile;
use Danilocgsilva\ConfigurationSpitter\DockerCompose;

class Receipt
{
    public function get(): array
    {
        return [
            "docker-compose.yml" => (new DockerCompose())->getString(),
            "DockerFile" => (new DockerFile())->getString()
        ];
    }
}
