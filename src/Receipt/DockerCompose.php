<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

use Symfony\Component\Yaml\Yaml;

class DockerCompose implements SpitterInterface
{
    private string $mariaDbPassword = "";

    public function getString(): string
    {
        if ($this->mariaDbPassword !== "") {
            $data = [
                'services' => [
                    'env' => [
                        'build' => [
                            'context' => '.'
                        ],
                        'links' => [
                            'mariadb'
                        ]
                    ],
                    'mariadb' => [
                        'image' => 'mariadb:latest',
                        'environment' => [
                            'MARIADB_ROOT_PASSWORD' => '%s'
                        ]
                    ]
                ]
            ];
            
            $baseString = Yaml::dump($data, 5, 2);
            $expectedString = sprintf($baseString, $this->mariaDbPassword);
        } else {
            $data = [
                'services' => [
                    'env' => [
                        'build' => [
                            'context' => '.'
                        ]
                    ]
                ]
            ];

            $expectedString = Yaml::dump($data, 5, 2);
        }


        return $expectedString;
    }

    public function setMariaDb(string $rootPassword): self
    {
        $this->mariaDbPassword = $rootPassword;

        return $this;
    }
}
