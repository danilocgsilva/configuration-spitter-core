<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Symfony\Component\Yaml\Yaml;

class DockerCompose implements SpitterInterface
{
    private string $mariaDbPassword = "";

    public function getString(): string
    {
        $baseData = [
            'services' => [
                'env' => [
                    'build' => [
                        'context' => '.'
                    ]
                ]
            ]
        ];

        if ($this->mariaDbPassword !== "") {
            $baseData['services']['env']['links'] = ['mariadb'];
            $baseData['services']['mariadb'] = [
                'image' => 'mariadb:latest',
                'environment' => [
                    'MARIADB_ROOT_PASSWORD' => '%s'
                ]
            ];
            
            $baseString = Yaml::dump($baseData, 5, 2);
            return sprintf($baseString, $this->mariaDbPassword);
        }

        return Yaml::dump($baseData, 5, 2);
    }

    public function setMariaDb(string $rootPassword): self
    {
        $this->mariaDbPassword = $rootPassword;

        return $this;
    }
}
