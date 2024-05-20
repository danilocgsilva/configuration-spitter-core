<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData;
use Symfony\Component\Yaml\Yaml;

class DockerCompose implements SpitterInterface
{
    private string $mariaDbPassword = "";

    public function getString(): string
    {
        $baseData = [
            'services' => [
                'env' => (new DebianServiceData())->getData()
            ]
        ];

        if ($this->mariaDbPassword !== "") {
            $baseData['services']['env']['links'] = ['mariadb'];
            $baseData['services']['mariadb'] = (new MariadbServiceData())->getData();
            
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
