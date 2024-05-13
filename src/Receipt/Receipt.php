<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerCompose;

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
