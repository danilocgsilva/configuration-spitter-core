<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
use Danilocgsilva\ConfigurationSpitter\DockerFile;
use Exception;

class DebianReceipt extends AbstractReceipt implements ReceiptInterface
{
    private string $extraExplanationString = "";

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
        $this->parameters = [
            "update",
            "upgrade",
            "add-maria-db-client-with-password",
            "mariadb-server-and-client",
            "port-redirection",
            "service-name",
            "container-name"
        ];
    }

    public function setProperty(string $propertyWithParameter): self
    {
        $validations = $this->validateParameters($propertyWithParameter);
        if ($validations['property'] === "update") {
            $this->dockerFile->setUpdate();
        }
        if ($validations['property'] === "mysql") {
            $this->dockerFile->setMysql();
        }
        if ($validations['property'] === "upgrade") {
            $this->dockerFile->setUpgrade();
        }
        if ($validations['countTerms'] === 2) {
            if ($validations['property'] === "add-maria-db-client-with-password") {
                $this->dockerCompose->setMariaDb($validations['argument']);
            }
        }
        if ($validations['property'] === "mariadb-server-and-client") {
            $this->dockerFile->setMariaDbServer();
            $this->dockerFile->setMariaDbClient();
            $this->extraExplanationString .= "\nThe container will have mariadb server and client as well.";
        }
        if ($validations['property'] === "port-redirection") {
            $this->dockerCompose->setPortRedirection(80, 80);
            $this->extraExplanationString .= "\nIt will have redirection from port 80 from host to 80 of container.";
        }
        if ($validations['property'] === "service-name") {
            $this->dockerCompose->changeServiceName($validations['argument']);
        }
        if ($validations['property'] === "container-name") {
            $this->dockerCompose->setContainerName($validations['argument']);
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
}
