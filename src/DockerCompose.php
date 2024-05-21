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

    public function setServiceData(ServiceDataInterface $serviceData, string $serviceName)
    {
        $this->dataArray = [
            'services' => [
                $serviceName => $serviceData->getData()
            ]
        ];
    }

    public function getString(): string
    {
        return Yaml::dump($this->dataArray, 5, 2);
    }

    public function setMariaDb(string $rootPassword): self
    {
        $this->dataArray['services']['env']['links'] = ['mariadb'];
        $this->dataArray['services']['mariadb'] = (new MariadbServiceData())->getData();
        $this->dataArray['services']['mariadb']['environment']['MARIADB_ROOT_PASSWORD'] = $rootPassword;

        return $this;
    }
}
