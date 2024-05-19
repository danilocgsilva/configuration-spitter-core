<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerCompose;

class Receipt
{
    private DockerFile $dockerFile;

    private DockerCompose $dockerCompose;

    const PARAMETERS = [
        "update",
        "upgrade",
        "add-maria-db-client-with-password"
    ];

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
        $parametedPropertySections = explode(":", $property);
        if (count($parametedPropertySections) === 2) {
            $parameter = $parametedPropertySections[0];
            $password = $parametedPropertySections[1];
            if ($parameter === "add-maria-db-client-with-password") {
                $this->dockerCompose->setMariaDb($password);
            }
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
        return $this->dockerFile->explain();
    }

    public function getDockerFileObject(): DockerFile
    {
        return $this->dockerFile;
    }

    public function getDockerComposeObject(): DockerCompose
    {
        return $this->dockerCompose;
    }

    public function getParameters(): array
    {
        return self::PARAMETERS;
    }
}
