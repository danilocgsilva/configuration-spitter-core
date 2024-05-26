<?php

declare(strict_types=1);

namespace Tests\Unit\Receipt;

use Danilocgsilva\ConfigurationSpitter\Receipt\MariadbReceipt;
use PHPUnit\Framework\TestCase;
use Exception;

class MariadbReceiptTest extends TestCase
{
    private MariadbReceipt $mariadbReceipt;

    public function setUp(): void
    {
        $this->mariadbReceipt = new MariadbReceipt();
    }
    
    public function testGet(): void
    {
        $expectedFileData = <<<EOF
services:
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: ''

EOF;
        $expectedArray = [
            'docker-compose.yml' => $expectedFileData
        ];

        $this->assertSame($expectedArray, $this->mariadbReceipt->get());
    }

    public function testAddingNotExistingParameter(): void
    {
        $this->expectException(Exception::class);
        $this->mariadbReceipt->setProperty("ThisPropertyDoesNotExists");
    }

    public function testPortRedirection(): void
    {
        $expectedFileData = <<<EOF
services:
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: ''
    ports:
      - '4006:3306'

EOF;
        $this->mariadbReceipt->setProperty("port-redirect:4006");
        $filesData = $this->mariadbReceipt->get();

        $this->assertSame($expectedFileData, $filesData['docker-compose.yml']);
    }

    public function testExplainWithPortRedirectio(): void
    {
        $this->mariadbReceipt->setProperty("port-redirect:4006");
        $expectedExplanation = "Raise a mariadb service.";
        $expectedExplanation .= "\nSetted the redirection from 4006 to 3306.";
        $this->assertSame($expectedExplanation, $this->mariadbReceipt->explain());
    }

    public function testSetRootPassword(): void
    {
        $this->mariadbReceipt->setProperty("password:1234abcd");
        $expectedFileData = <<<EOF
services:
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: 1234abcd

EOF;
        $filesData = $this->mariadbReceipt->get();
        $this->assertSame($expectedFileData, $filesData['docker-compose.yml']);
    }

    public function testSettingPortRedirectionAndPassword(): void
    {
        $expectedFileData = <<<EOF
services:
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: abcd4321
    ports:
      - '3320:3306'

EOF;

        $this->mariadbReceipt->setProperty("password:abcd4321");
        $this->mariadbReceipt->setProperty("port-redirect:3320");

        $filesData = $this->mariadbReceipt->get();
        $this->assertSame($expectedFileData, $filesData['docker-compose.yml']);
    }

    public function testGetParameters()
    {
        $expectedParameters = [
            "port-redirect",
            "password"
        ];

        $this->assertSame($expectedParameters, $this->mariadbReceipt->getParameters());
    }
}
