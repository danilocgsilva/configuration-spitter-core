<?php

declare(strict_types=1);

namespace Tests\Unit\Receipt;

use Danilocgsilva\ConfigurationSpitter\Receipt\DebianReceipt;
use PHPUnit\Framework\TestCase;
use Danilocgsilva\ConfigurationSpitter\DockerFile;
use Danilocgsilva\ConfigurationSpitter\DockerCompose;
use Danilocgsilva\ConfigurationSpitter\ServicesData\DebianServiceData;
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

    public function testExplainWithUpdate(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "The folder nome will receive the time hash.\n";
        $expectedExplanation .= "It also perform an update in the operational system repository, so packages can be installed through default operating system utility.";

        $this->debianReceipt->setProperty("update");
        $this->assertSame($expectedExplanation, $this->debianReceipt->explain());
    }

    public function testExplainWithUpgrade(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "The folder nome will receive the time hash.\n";
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

    public function testExplain(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.";
        $expectedExplanation .= "\nThe folder nome will receive the time hash.";
        $this->assertSame($expectedExplanation, $this->debianReceipt->explain());
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

    public function testEexplanationWithMariadbClientAndServer(): void
    {
        $this->debianReceipt->setProperty("mariadb-server-and-client");

        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.";
        $expectedExplanation .= "\nThe folder nome will receive the time hash.";
        $expectedExplanation .= "\nThe container will have mariadb server and client as well.";

        $this->assertSame($expectedExplanation, $this->debianReceipt->explain());
    }

    public function testChangeServiceName(): void
    {
        $this->debianReceipt->setProperty("service-name:mydifferentservicename");
        $expectedString = <<<EOF
services:
  mydifferentservicename:
    build:
      context: .

EOF;
        $this->assertSame($expectedString, $this->debianReceipt->getDockerComposeObject()->getString());
    }

    public function testSetContainerName(): void
    {
        $this->debianReceipt->setProperty("container-name:my_debian_container");
        $expectedString = <<<EOF
services:
  env:
    build:
      context: .
    container_name: my_debian_container

EOF;
        $this->assertSame($expectedString, $this->debianReceipt->getDockerComposeObject()->getString());
    }

    public function testSetPhpApache(): void
    {
        $this->debianReceipt->setProperty("add-php-apache");

        $expectedString = <<<EOF
services:
  env:
    build:
      context: .

EOF;
        $this->assertSame($expectedString, $this->debianReceipt->getDockerComposeObject()->getString());
    }

    public function testSetPhpApacheAndDockerfile(): void
    {
        $this->debianReceipt->setProperty("add-php-apache");
        $dockerFile = $this->debianReceipt->getDockerFileObject();
        $this->assertInstanceOf(DockerFile::class, $dockerFile);
    }

    public function testSetPhpApacheAndDockerfileWithData(): void
    {
        $this->debianReceipt->setProperty("add-php-apache");
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get install php -y

CMD while : ; do sleep 1000; done
EOF;

        $dockerFile = $this->debianReceipt->getDockerFileObject();

        $this->assertSame($expectedString, $dockerFile->getString());
    }

    public function testSetFullPhpApacheDev(): void
    {
        $this->debianReceipt->setProperty("set-full-php-apache-dev");
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get install curl git zip -y
RUN apt-get install php php-mysql php-xdebug php-curl php-zip php-xml -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

CMD while : ; do sleep 1000; done
EOF;

        $dockerFile = $this->debianReceipt->getDockerFileObject();
        $this->assertSame($expectedString, $dockerFile->getString());
    }
}
