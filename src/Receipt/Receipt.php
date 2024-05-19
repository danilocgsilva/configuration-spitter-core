<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerCompose;

class Receipt
{
    private DockerFile $dockerFile;

    private DockerCompose $dockerCompose;

    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerFile = new DockerFile();
    }
    
    public function setProperty(string $property): self
    {
        if ($property === "update") {
            $this->dockerFile->setUpdate();
        }
        if ($property === "upgrade") {
            $this->dockerFile->setUpgrade();
        }
        return $this;
    }
    
    public function get(): array
    {
        return [
            "docker-compose.yml" => $this->dockerCompose->getString(),
            "DockerFile" => $this->dockerFile->getString()
        ];
    }

    public function explain(): string
    {
        $dockerFile = new DockerFile();
        return $dockerFile->explain();
    }

    public function getDockerFileObject(): DockerFile
    {
        return $this->dockerFile;
    }
}
