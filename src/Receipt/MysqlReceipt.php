<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MysqlServiceData;
use Exception;

class MysqlReceipt implements ReceiptInterface
{
    private string $explanationString = "Raise a mysql service.";

    private DockerCompose $dockerCompose;

    const PARAMETERS = [
        "port-redirect"
    ];
    
    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerCompose->setServiceData(new MysqlServiceData(), 'mariadb');
    }

    public function explain(): string
    {
        return $this->explanationString;
    }

    public function getParameters(): array
    {
        return [];
    }

    public function get(): array
    {
        return [
            "docker-compose.yml" => $this->dockerCompose->getString()
        ];
    }

    public static function getName(): string
    {
        return "Mysql";
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
            $portRedirection = (int) $propertyWithParameterArray[1];
            if ($parameter === "port-redirect") {
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData */
                $mariadbServiceData = $this->dockerCompose->getServiceData();
                $mariadbServiceData->setPortRedirection($portRedirection);
                $this->explanationString .= "\nSetted the redirection from $portRedirection to 3306.";
            }
        }
        return $this;
    }
}
