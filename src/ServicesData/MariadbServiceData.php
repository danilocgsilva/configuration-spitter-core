<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\ServicesData;

class MariadbServiceData extends AbstractServiceData implements ServiceDataInterface
{
    public function __construct()
    {
        $this->data = [
            'image' => 'mariadb:latest',
            'environment' => [
                'MARIADB_ROOT_PASSWORD' => ''
            ]
        ];
    }

    public function setPortRedirection(int $port)
    {
        $this->data['ports'] = [ $port . ':3306' ];
    }
    
    public function getData(): array
    {
        return $this->data;
    }

    public function setRootPassword(string $password)
    {
        $this->data['environment'] = [
            'MARIADB_ROOT_PASSWORD' => $password
        ];
    }

    public function setContainerName(string $containerName): self
    {
        $this->data['container_name'] = $containerName;
        return $this;
    }
}
