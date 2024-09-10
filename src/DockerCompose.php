<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

use Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData;
use Danilocgsilva\ConfigurationSpitter\ServicesData\ServiceDataInterface;
use Symfony\Component\Yaml\Yaml;
use Exception;

class DockerCompose implements SpitterInterface
{
    private array $dataArray = [];

    private ServiceDataInterface $serviceData;

    private string $serviceName;

    protected bool $appFolder = false;

    public function onAppFolder(): self
    {
        $this->appFolder = true;
        return $this;
    }

    public function setServiceData(ServiceDataInterface $serviceData, string $serviceName)
    {
        $this->serviceData = $serviceData;
        $this->serviceName = $serviceName;
    }

    public function changeServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    public function getString(): string
    {
        if (!count($this->dataArray)) {
            $this->buildDataArray();
        }
        return Yaml::dump($this->dataArray, 5, 2);
    }

    public function setMariaDb(string $rootPassword): self
    {
        $this->buildDataArray();
        $this->dataArray['services'][$this->serviceName]['links'] = ['mariadb'];
        $this->dataArray['services']['mariadb'] = (new MariadbServiceData())->getData();
        $this->dataArray['services']['mariadb']['environment']['MARIADB_ROOT_PASSWORD'] = $rootPassword;

        return $this;
    }

    public function getServiceData(): ServiceDataInterface
    {
        return $this->serviceData;
    }

    public function setPortRedirection(int $hostPort, int $containerPort): self
    {
        $this->exceptIfMissingDataService();
        $this->buildDataArray();
        $this->dataArray['services'][$this->serviceName]['ports'] = [ sprintf("%s:%s", $hostPort, $containerPort) ];
        return $this;
    }

    public function setContainerName(string $containerName): self
    {
        if (!method_exists($this->serviceData, "setContainerName")) {
            throw new Exception("The setContainerName shoud not be used with this receipt.");
        }
        $this->serviceData->setContainerName($containerName);
        return $this;
    }

    private function buildDataArray(): void
    {
        $this->dataArray = [
            'services' => [
                $this->serviceName => $this->serviceData->getData()
            ]
        ];

        if ($this->appFolder) {
            $this->dataArray['services'][$this->serviceName]['volumes'][] = "./app:/app";
        }
    }

    private function exceptIfMissingDataService(): void
    {
        if (!isset($this->serviceData)) {
            throw new Exception("You need first give a service class. Use self::setServiceData before any class operation.");
        }
    }
}
