<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData;
use Danilocgsilva\ConfigurationSpitter\ServicesData\ServiceDataInterface;
use Symfony\Component\Yaml\Yaml;

class DockerCompose implements SpitterInterface
{
    private array $dataArray = [];

    private ServiceDataInterface $serviceData;

    private string $serviceName;

    public function setServiceData(ServiceDataInterface $serviceData, string $serviceName)
    {
        $this->serviceData = $serviceData;
        $this->serviceName = $serviceName;
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
        $this->dataArray['services']['env']['links'] = ['mariadb'];
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
        return $this;
    }

    private function buildDataArray(): void
    {
        $this->dataArray = [
            'services' => [
                $this->serviceName => $this->serviceData->getData()
            ]
        ];
    }
}
