<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\ServicesData;

class DebianServiceData implements ServiceDataInterface
{
    public function getData(): array
    {
        return [
            'build' => [
                'context' => '.'
            ]
        ];
    }
}
