<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\ServicesData;

class NodeServiceData implements ServiceDataInterface
{
    public function getData(): array
    {
        return [
            'image' => 'node:latest'
        ];
    }
}
