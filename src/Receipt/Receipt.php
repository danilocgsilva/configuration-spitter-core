<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerCompose;

class Receipt
{
    private array $services = [];
    
    public function set(string $service): void
    {
        $this->services[] = $service;
    }
    
    public function get(): array
    {
        $dockerComposer = new DockerCompose();
        $dockerFile = new DockerFile();
        
        return [
            "docker-compose.yml" => $dockerComposer->getString(),
            "DockerFile" => $dockerFile->getString()
        ];
    }
}
