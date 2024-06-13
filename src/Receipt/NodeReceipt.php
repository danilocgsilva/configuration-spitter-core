<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\NodeServiceData;
use Exception;

class NodeReceipt extends AbstractReceipt implements ReceiptInterface
{
    private string $explanationString = "Creates a basic node receipt.";

    public static function getName(): string
    {
        return "Node";
    }

    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerCompose->setServiceData(new NodeServiceData(), 'node');
        $this->parameters = [
            "container-name"
        ];
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

    public function setProperty(string $propertyWithParameter): self
    {
        $validations = $this->validateParameters($propertyWithParameter);
        return $this;
    }
}
