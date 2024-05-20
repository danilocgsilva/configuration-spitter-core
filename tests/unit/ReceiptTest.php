<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ConfigurationSpitter\Receipt\Receipt;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerCompose;
use Exception;

class ReceiptTest extends TestCase
{
    public function testGet(): void
    {
        $receipt = new Receipt();
        $dockerFile = new DockerFile();
        $dockerCompose = new DockerCompose();

        $receipt = new Receipt();
        $receiptData = $receipt->get();

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
        $receipt = new Receipt();
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.";
        $this->assertSame($expectedExplanation, $receipt->explain());
    }

    public function testExplainWithUpdate(): void
    {
        $receipt = new Receipt();

        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "It also perform an update in the operational system repository, so packages can be installed through default operating system utility.";

        $receipt->setProperty("update");
        $this->assertSame($expectedExplanation, $receipt->explain());
    }

    public function testExplainWithUpgrade(): void
    {
        $receipt = new Receipt();

        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "Will update operating system packages.";

        $receipt->setProperty("upgrade");
        $this->assertSame($expectedExplanation, $receipt->explain());
    }

    public function testPropertyassigment(): void
    {
        $receipt = new Receipt();
        $receipt
            ->setProperty("update")
            ->setProperty("upgrade");

        $dockerFile = $receipt->getDockerFileObject();

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
        $receipt = new Receipt();
        $receipt->setProperty("add-maria-db-client-with-password:themariadbpassword");
        $dockerCompose = $receipt->getDockerComposeObject();
        $expectedString = <<<EOF
services:
  env:
    build:
      context: .
    links:
      - mariadb
  mariadb:
    image: mariadb:latest
    environment:
      MARIADB_ROOT_PASSWORD: "themariadbpassword"
EOF;

        $this->assertSame($expectedString, $dockerCompose->getString());
    }

    public function testAddingNotExistingParameter(): void
    {
        $this->expectException(Exception::class);
        $receipt = new Receipt();
        $receipt->setProperty("ThisPropertyDoesNotExists");
    }
}
