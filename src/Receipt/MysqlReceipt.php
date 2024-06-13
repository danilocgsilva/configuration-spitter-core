<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MysqlServiceData;
use Exception;

class MysqlReceipt extends AbstractReceipt implements ReceiptInterface
{
    private string $explanationString = "Raise a mysql service.";

    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerCompose->setServiceData(new MysqlServiceData(), 'mysql');
        $this->parameters = [
            "port-redirect",
            "password",
            "container-name"
        ];
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

    public static function getName(): string
    {
        return "Mysql";
    }

    public function setProperty(string $propertyWithParameter): self
    {
        $validations = $this->validateParameters($propertyWithParameter);
        if ($validations['countTerms'] === 2) {
            $parameter = $validations['property'];
            $portRedirection = (int) $validations['argument'];
            if ($parameter === "port-redirect") {
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MysqlServiceData */
                $mysqlServiceData = $this->dockerCompose->getServiceData();
                $mysqlServiceData->setPortRedirection($portRedirection);
                $this->explanationString .= "\nSetted the redirection from $portRedirection to 3306.";
            }
            if ($parameter === "password") {
                $rootPassword = $validations['argument'];
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MysqlServiceData */
                $mysqlServiceData = $this->dockerCompose->getServiceData();
                $mysqlServiceData->setRootPassword($rootPassword);
            }
        }
        return $this;
    }
}
