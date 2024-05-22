<?php

declare(strict_types=1);

namespace Tests\Unit;

use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
use Danilocgsilva\ConfigurationSpitter\ServicesData\MariadbServiceData;
use PHPUnit\Framework\TestCase;

class DockerComposeTest extends TestCase
{
    private DockerCompose $dockerCompose;

    public function setUp(): void
    {
        $this->dockerCompose = new DockerCompose();
    }
    
    public function testGetString(): void
    {
        $expectedString = <<<EOF
services:
  env:
    build:
      context: .

EOF;
        $this->dockerCompose->setServiceData(new DebianServiceData(), 'env');

        $this->assertSame($expectedString, $this->dockerCompose->getString());
    }

    public function testGetStringWithMariaDb(): void
    {
        $this->dockerCompose->setServiceData(new DebianServiceData(), 'env');
        $this->dockerCompose->setMariaDb("mySuperSecurePassword");
      
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

        $this->assertSame($expectedString, $this->dockerCompose->getString());
    }

    public function testGetStringWithMariaDb2(): void
    {
        $this->dockerCompose->setServiceData(new DebianServiceData(), 'env');
        $this->dockerCompose->setMariaDb("anotherSecure%$#password");
      
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

        $this->assertSame($expectedString, $this->dockerCompose->getString());
    }

    public function testGetStringForDatabase(): void
    {
        $this->dockerCompose->setServiceData(new MariadbServiceData(), 'mariadb');
        $expectedString = <<<EOF
services:
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: ''

EOF;

        $this->assertSame($expectedString, $this->dockerCompose->getString());
    }

    public function testSetPortRedirection(): void
    {
        $this->dockerCompose->setPortRedirection(80, 80);
        $expectedString = <<<EOF
services:
  env:
    build:
      context: .
    ports:
      - "80:80"

EOF;
        $this->assertSame($expectedString, $this->dockerCompose->getString());
    }
}
