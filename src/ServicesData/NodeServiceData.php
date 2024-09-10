<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\ServicesData;

class NodeServiceData extends AbstractServiceData implements ServiceDataInterface
{
    public function __construct()
    {
        $this->data = [
            'image' => 'node:latest'
        ];
    }

    public function getData(): array
    {
        return $this->data;
    }
}
