<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\NodeServiceData;
use Exception;

class NodeReceipt implements ReceiptInterface
{
    private string $explanationString = "Creates a basic node receipt.";

    private DockerCompose $dockerCompose;

    const PARAMETERS = [
    ];

    public static function getName(): string
    {
        return "Node";
    }

    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerCompose->setServiceData(new NodeServiceData(), 'node');
    }
    
    public function get(): array
    {
        return [
            "docker-compose.yml" => $this->dockerCompose->getString()
        ];
    }

    public function explain(): string
    {
        return $this->explanationString;
    }

    public function getDockerComposeObject(): DockerCompose
    {
        return $this->dockerCompose;
    }

    public function getParameters(): array
    {
        return self::PARAMETERS;
    }

    public function setProperty(string $propertyWithParameter): self
    {
        $propertyWithParameterArray = explode(":", $propertyWithParameter);
        $property = $propertyWithParameterArray[0];
        if (!in_array($property, self::PARAMETERS)) {
            throw new Exception("The given property is not expected to be received.");
        }
        return $this;
    }
}
