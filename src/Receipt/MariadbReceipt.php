<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData;
use Exception;

class MariadbReceipt implements ReceiptInterface
{
    private DockerCompose $dockerCompose;

    private string $explanationString = "Raise a mariadb service.";

    public static function getName(): string
    {
        return "MariaDB";
    }

    const PARAMETERS = [
        "port-redirect",
        "password"
    ];

    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerCompose->setServiceData(new MariadbServiceData(), 'mariadb');
    }

    public function explain(): string
    {
        return $this->explanationString;
    }

    public function get(): array
    {
        return [
            "docker-compose.yml" => $this->dockerCompose->getString()
        ];
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
        if (count($propertyWithParameterArray) === 2) {
            $parameter = $propertyWithParameterArray[0];
            if ($parameter === "port-redirect") {
                $portRedirection = (int) $propertyWithParameterArray[1];
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData */
                $mariadbServiceData = $this->dockerCompose->getServiceData();
                $mariadbServiceData->setPortRedirection($portRedirection);
                $this->explanationString .= "\nSetted the redirection from $portRedirection to 3306.";
            }
            if ($parameter === "password") {
                $rootPassword = $propertyWithParameterArray[1];
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData */
                $mariadbServiceData = $this->dockerCompose->getServiceData();
                $mariadbServiceData->setRootPassword($rootPassword);
            }
        }
        return $this;
    }
}
