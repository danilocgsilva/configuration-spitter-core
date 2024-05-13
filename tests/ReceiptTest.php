<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ConfigurationSpitter\Receipt;
use Danilocgsilva\ConfigurationSpitter\DockerFile;
use Danilocgsilva\ConfigurationSpitter\DockerCompose;

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
}
