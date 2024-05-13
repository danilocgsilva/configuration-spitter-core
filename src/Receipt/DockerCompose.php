<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

class DockerCompose implements SpitterInterface
{
    public function getString(): string
    {
        return <<<EOF
services:
  env:
    build:
      context: .
EOF;
    }
}
