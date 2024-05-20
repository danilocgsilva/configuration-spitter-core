<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData;

class MariadbReceipt implements ReceiptInterface
{
    const PARAMETERS = [
        "port-redirect"
    ];

    public function explain(): string
    {
        return "Raise a mariadb service.";
    }

    public function get(): array
    {
        $dockerCompose = new DockerCompose();
        
        return [
            "docker-compose.yml" => 'a'
        ];
    }

    public function getParameters(): array
    {
        return self::PARAMETERS;
    }
}
