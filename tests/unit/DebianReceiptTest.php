<?php

declare(strict_types=1);

namespace Tests\Unit;

use Danilocgsilva\ConfigurationSpitter\Receipt\DebianReceipt;
use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
use PHPUnit\Framework\TestCase;
use Danilocgsilva\ConfigurationSpitter\DockerFile;
use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Exception;

class DebianReceiptTest extends TestCase
{
    private DebianReceipt $debianReceipt;

    public function setUp(): void
    {
        $this->debianReceipt = new DebianReceipt();
    }
    
    public function testGet(): void
    {
        $dockerFile = new DockerFile();
        $dockerCompose = new DockerCompose();
        $dockerCompose->setServiceData(new DebianServiceData(), 'env');

        $receiptData = $this->debianReceipt->get();

        $this->assertCount(2, $receiptData);

        $this->assertSame(
            $dockerFile->getString(),
            $receiptData['DockerFile']
        );

        $this->assertSame(
            $dockerCompose->getString(),
            $receiptData['docker-compose.yml']
        );
    }

    public function testExplain(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.";
        $this->assertSame($expectedExplanation, $this->debianReceipt->explain());
    }

    public function testExplainWithUpdate(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "It also perform an update in the operational system repository, so packages can be installed through default operating system utility.";

        $this->debianReceipt->setProperty("update");
        $this->assertSame($expectedExplanation, $this->debianReceipt->explain());
    }

    public function testExplainWithUpgrade(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "Will update operating system packages.";

        $this->debianReceipt->setProperty("upgrade");
        $this->assertSame($expectedExplanation, $this->debianReceipt->explain());
    }

    public function testPropertyassigment(): void
    {
        $this->debianReceipt
            ->setProperty("update")
            ->setProperty("upgrade");

        $dockerFile = $this->debianReceipt->getDockerFileObject();

        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update
RUN apt-get upgrade -y

CMD while : ; do sleep 1000; done
EOF;

        $this->assertSame($expectedString, $dockerFile->getString());
    }

    public function testSetMariaDb(): void
    {
        $this->debianReceipt->setProperty("add-maria-db-client-with-password:themariadbpassword");
        $dockerCompose = $this->debianReceipt->getDockerComposeObject();
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
      MARIADB_ROOT_PASSWORD: themariadbpassword

EOF;

        $this->assertSame($expectedString, $dockerCompose->getString());
    }

    public function testAddingNotExistingParameter(): void
    {
        $this->expectException(Exception::class);
        $this->debianReceipt->setProperty("ThisPropertyDoesNotExists");
    }
}
