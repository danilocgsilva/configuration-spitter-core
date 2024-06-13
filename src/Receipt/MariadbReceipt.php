<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData;
use Exception;

class MariadbReceipt extends AbstractReceipt implements ReceiptInterface
{
    private array $explanationData = [
        "Raise a mariadb service.",
        "There's no port redirection setted yet - will use the 3306."
    ];
    
    public static function getName(): string
    {
        return "MariaDB";
    }

    public function __construct()
    {
        $this->dockerCompose = new DockerCompose();
        $this->dockerCompose->setServiceData(new MariadbServiceData(), 'mariadb');
        $this->parameters = [
            "port-redirect",
            "password",
            "container-name"
        ];
    }

    public function explain(): string
    {
        return implode("\n", $this->explanationData);
    }

    public function get(): array
    {
        return [
            "docker-compose.yml" => $this->dockerCompose->getString()
        ];
    }

    public function setProperty(string $propertyWithParameter): self
    {
        $validations = $this->validateParameters($propertyWithParameter);
        if ($validations['countTerms'] === 2) {
            $parameter = $validations['property'];
            if ($parameter === "port-redirect") {
                $portRedirection = (int) $validations['argument'];
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData */
                $mariadbServiceData = $this->dockerCompose->getServiceData();
                $mariadbServiceData->setPortRedirection($portRedirection);
                unset($this->explanationData[1]);
                $this->explanationData[] = "Setted the redirection from $portRedirection to 3306.";
            }
            if ($parameter === "password") {
                $rootPassword = $validations['argument'];
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData */
                $mariadbServiceData = $this->dockerCompose->getServiceData();
                $mariadbServiceData->setRootPassword($rootPassword);
            }
            if ($parameter === "container-name") {
                /** @var \Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData */
                $mariadbServiceData = $this->dockerCompose->getServiceData();
                $mariadbServiceData->setContainerName($validations['argument']);
            }
        }
        return $this;
    }
}
