<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerFile;
use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
use Exception;

class DebianReceipt implements ReceiptInterface
{
    private DockerFile $dockerFile;

    private DockerCompose $dockerCompose;

    const PARAMETERS = [
        "update",
        "upgrade",
        "add-maria-db-client-with-password",
        "mariadb-server-and-client"
    ];

    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerCompose->setServiceData(new DebianServiceData(), 'env');
        $this->dockerFile = new DockerFile();
    }
    
    public function setProperty(string $propertyWithParameter): self
    {
        $propertyWithParameterArray = explode(":", $propertyWithParameter);
        $property = $propertyWithParameterArray[0];
        if (!in_array($property, self::PARAMETERS)) {
            throw new Exception("The given property is not expected to be received.");
        }
        if ($property === "update") {
            $this->dockerFile->setUpdate();
        }
        if ($property === "upgrade") {
            $this->dockerFile->setUpgrade();
        }
        if (count($propertyWithParameterArray) === 2) {
            $parameter = $propertyWithParameterArray[0];
            $password = $propertyWithParameterArray[1];
            if ($parameter === "add-maria-db-client-with-password") {
                $this->dockerCompose->setMariaDb($password);
            }
        }
        if ($property === "mariadb-server-and-client") {
            $this->dockerFile->setMariaDbServer();
            $this->dockerFile->setMariaDbClient();
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
