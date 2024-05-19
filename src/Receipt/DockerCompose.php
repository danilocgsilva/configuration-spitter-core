<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

class DockerCompose implements SpitterInterface
{
    private string $mariaDbPassword = "";

    public function getString(): string
    {
        if ($this->mariaDbPassword !== "") {
            $baseString = <<<EOF
services:
  env:
    build:
      context: .
    links:
      - mariadb
  mariadb:
    image: mariadb:latest
    environment:
      MARIADB_ROOT_PASSWORD: "%s"
EOF;
            $expectedString = sprintf($baseString, $this->mariaDbPassword);
        } else {
            $expectedString = <<<EOF
services:
  env:
    build:
      context: .
EOF;
        }
        
        return $expectedString;
    }

    public function setMariaDb(string $rootPassword): self
    {
        $this->mariaDbPassword = $rootPassword;

        return $this;
    }
}
