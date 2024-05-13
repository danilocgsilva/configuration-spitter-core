<?php

declare(strict_types=1);

namespace Tests;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use PHPUnit\Framework\TestCase;

class DockerComposeTest extends TestCase
{
    public function testGetString(): void
    {
        $expectedString = <<<EOF
services:
  env:
    build:
      context: .
EOF;
        $dockerCompose = new DockerCompose();

        $this->assertSame($expectedString, $dockerCompose->getString());
    }
}
