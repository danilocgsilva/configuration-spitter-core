<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\ServicesData;

class MariadbServiceData implements ServiceDataInterface
{
    public function getData(): array
    {
        return [
            'image' => 'mariadb:latest',
            'environment' => [
                'MARIADB_ROOT_PASSWORD' => '%s'
            ]
        ];
    }
}
