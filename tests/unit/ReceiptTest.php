<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ConfigurationSpitter\Receipt\Receipt;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use Danilocgsilva\ConfigurationSpitter\Receipt\DockerCompose;

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
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholser";
        $this->assertSame($expectedExplanation, $receipt->explain());
    }
}
