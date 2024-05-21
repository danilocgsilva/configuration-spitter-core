<?php

declare(strict_types=1);

namespace Tests\Unit;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
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
        $dockerCompose->setServiceData(new DebianServiceData());

        $this->assertSame($expectedString, $dockerCompose->getString());
    }

    public function testGetStringWithMariaDb(): void
    {
        $dockerCompose = new DockerCompose();
        $dockerCompose->setServiceData(new DebianServiceData());
        $dockerCompose->setMariaDb("mySuperSecurePassword");
      
        $expectedString = <<<EOF
services:
  env:
    build:
      context: .
    links:
      - mariadb
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: mySuperSecurePassword

EOF;

        $this->assertSame($expectedString, $dockerCompose->getString());
    }

    public function testGetStringWithMariaDb2(): void
    {
        $dockerCompose = new DockerCompose();
        $dockerCompose->setServiceData(new DebianServiceData());
        $dockerCompose->setMariaDb("anotherSecure%$#password");
      
        $expectedString = <<<EOF
services:
  env:
    build:
      context: .
    links:
      - mariadb
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: 'anotherSecure%$#password'

EOF;

        $this->assertSame($expectedString, $dockerCompose->getString());
    }
}
