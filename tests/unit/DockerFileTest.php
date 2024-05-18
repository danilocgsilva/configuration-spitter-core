<?php

declare(strict_types=1);

namespace Tests\Unit;

use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use PHPUnit\Framework\TestCase;

class DockerFileTest extends TestCase
{
    private DockerFile $dockerFile;

    public function setUp(): void
    {
        $this->dockerFile = new DockerFile();
    }
    
    public function testGetString(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

CMD while : ; do sleep 1000; done
EOF;
        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testWithUpdate(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile->setUpdate();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testWithUpdateAndUpgrade(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update
RUN apt-get upgrade -y

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile->setUpdate()->setUpgrade();
        
        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testExplain(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholser";
        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }
}
