<?php

declare(strict_types=1);

namespace Tests\Unit;

use Danilocgsilva\ConfigurationSpitter\Receipt\DockerFile;
use PHPUnit\Framework\TestCase;

class DockerFileTest extends TestCase
{
    public function testGetString(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

CMD while : ; do sleep 1000; done
EOF;
        $dockerFile = new DockerFile();

        $this->assertSame($expectedString, $dockerFile->getString());
    }
}
