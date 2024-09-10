<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\ServicesData;

class DebianServiceData extends AbstractServiceData implements ServiceDataInterface
{
    public function __construct()
    {
        $this->data = [
            'build' => [
                'context' => '.'
            ]
        ];
    }
    
    public function getData(): array
    {
        return $this->data;
    }
    public function setContainerName(string $containerName): self
    {
        $this->data['container_name'] = $containerName;
        return $this;
    }
}
