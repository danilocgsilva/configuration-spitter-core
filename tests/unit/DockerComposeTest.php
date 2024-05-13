<?php

declare(strict_types=1);

namespace Tests\Unit;

use Danilocgsilva\ConfigurationSpitter\Receipt\DockerCompose;
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
