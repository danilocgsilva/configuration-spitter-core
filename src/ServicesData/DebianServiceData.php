<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\ServicesData;

class DebianServiceData implements ServiceDataInterface
{
    private array $data = [
        'build' => [
            'context' => '.'
        ]
    ];

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
