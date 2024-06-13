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

    private string $extraExplanationString = "";

    private DockerCompose $dockerCompose;

    const PARAMETERS = [
        "update",
        "upgrade",
        "add-maria-db-client-with-password",
        "add-mysql",
        "mariadb-server-and-client",
        "port-redirection",
        "service-name"
    ];

    public static function getName(): string
    {
        return "Debian";
    }

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
        if ($property === "mysql") {
            $this->dockerFile->setMysql();
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
            $this->extraExplanationString .= "\nThe container will have mariadb server and client as well.";
        }
        if ($property === "port-redirection") {
            $this->dockerCompose->setPortRedirection(80, 80);
            $this->extraExplanationString .= "\nIt will have redirection from port 80 from host to 80 of container.";
        }
        if ($property === "service-name") {
            $this->dockerCompose->changeServiceName($propertyWithParameterArray[1]);
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
        $explanationString = $this->dockerFile->explain();
        if ($this->extraExplanationString !== "") {
            $explanationString .= $this->extraExplanationString;
        }
        return $explanationString;
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
