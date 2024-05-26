<?php

declare(strict_types=1);

namespace Tests\Unit\Receipt;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ConfigurationSpitter\Receipt\MysqlReceipt;
use Exception;

class MysqlReceiptTest extends TestCase
{
    private MysqlReceipt $mysqlReceipt;

    public function setUp(): void
    {
        $this->mysqlReceipt = new MysqlReceipt();
    }

    public function testGet(): void
    {
        $expectedFileData = <<<EOF
services:
  mysql:
    image: 'mysql:latest'
    environment:
      MYSQL_ROOT_PASSWORD: ''

EOF;
        $expectedArray = [
            'docker-compose.yml' => $expectedFileData
        ];

        $this->assertSame($expectedArray, $this->mysqlReceipt->get());
    }

    public function testAddingNotExistingParameter(): void
    {
        $this->expectException(Exception::class);
        $this->mysqlReceipt->setProperty("ThisPropertyDoesNotExists");
    }

    public function testPortRedirection(): void
    {
        $expectedFileData = <<<EOF
services:
  mysql:
    image: 'mysql:latest'
    environment:
      MYSQL_ROOT_PASSWORD: ''
    ports:
      - '3316:3306'

EOF;
        $this->mysqlReceipt->setProperty("port-redirect:3316");
        $filesData = $this->mysqlReceipt->get();

        $this->assertSame($expectedFileData, $filesData['docker-compose.yml']);
    }

    public function testExplainWithPortRedirectio(): void
    {
        $this->mysqlReceipt->setProperty("port-redirect:3318");
        $expectedExplanation = "Raise a mysql service.";
        $expectedExplanation .= "\nSetted the redirection from 3318 to 3306.";
        $this->assertSame($expectedExplanation, $this->mysqlReceipt->explain());
    }

    public function testSetRootPassword(): void
    {
        $this->mysqlReceipt->setProperty("password:ffdasd66632");
        $expectedFileData = <<<EOF
services:
  mysql:
    image: 'mysql:latest'
    environment:
      MYSQL_ROOT_PASSWORD: ffdasd66632

EOF;
        $filesData = $this->mysqlReceipt->get();
        $this->assertSame($expectedFileData, $filesData['docker-compose.yml']);
    }

    public function testSettingPortRedirectionAndPassword(): void
    {
        $expectedFileData = <<<EOF
services:
  mysql:
    image: 'mysql:latest'
    environment:
      MYSQL_ROOT_PASSWORD: kjh45324
    ports:
      - '3420:3306'

EOF;

        $this->mysqlReceipt->setProperty("password:kjh45324");
        $this->mysqlReceipt->setProperty("port-redirect:3420");

        $filesData = $this->mysqlReceipt->get();
        $this->assertSame($expectedFileData, $filesData['docker-compose.yml']);
    }

    public function testGetParameters()
    {
        $expectedParameters = [
            "port-redirect",
            "password"
        ];

        $this->assertSame($expectedParameters, $this->mysqlReceipt->getParameters());
    }
}
