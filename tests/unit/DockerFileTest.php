<?php

declare(strict_types=1);

namespace Tests\Unit;

use Danilocgsilva\ConfigurationSpitter\DockerFile;
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

    public function testMysql(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get install mysql -y

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile->setMysql();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testExplain(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.";
        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }

    public function testExplainWithUpdate(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "It also perform an update in the operational system repository, so packages can be installed through default operating system utility.";
        $this->dockerFile->setUpdate();
        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }

    public function testExplainWithUpgrade(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "Will update operating system packages.";

        $this->dockerFile->setUpgrade();
        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }

    public function testExplainMysql(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "The container will be shipped with mysql.";
        $this->dockerFile->setMysql();
        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }

    public function testExplainMariadbclient(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "The Mariadb client will be added to the container.";
        $this->dockerFile->setMariadbClient();
        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }

    public function testMariadbClient(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install mariadb-client -y

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile
            ->setUpdate()
            ->setUpgrade()
            ->setMariadbClient();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testSingleMariadbClient(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get install mariadb-client -y

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile
            ->setMariadbClient();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testMysqlWithUpdates(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install mysql -y

CMD while : ; do sleep 1000; done
EOF;
        $this
            ->dockerFile
            ->setUpdate()
            ->setUpgrade()
            ->setMysql();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testMariadbServer(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install mariadb-server -y

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile
            ->setUpdate()
            ->setUpgrade()
            ->setMariadbServer();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testPhpApache(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get install php -y

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile
            ->setPhpApache();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }


    public function testPhpApacheWithDebianUpdates(): void
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install php -y

CMD while : ; do sleep 1000; done
EOF;
        $this->dockerFile
            ->setUpdate()
            ->setUpgrade()
            ->setPhpApache();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testExplainWithPhp(): void
    {
        $expectedString = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedString .= "Installs php with Apache together as well.";
        $this->dockerFile->setPhpApache();
        $this->assertSame($expectedString, $this->dockerFile->explain());
    }

    public function testFullPhpApacheDev(): void
    {
        $this->dockerFile->setFullPhpApacheDev();
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get install curl git zip -y
RUN apt-get install php php-mysql php-xdebug php-curl php-zip php-xml php-mbstring -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
COPY config/20-xdebug.ini /etc/php/8.2/apache2/conf.d/

CMD while : ; do sleep 1000; done
EOF;

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testFullPhpDevWithUpdates()
    {
        $expectedString = <<<EOF
FROM debian:bookworm-slim

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install curl git zip -y
RUN apt-get install php php-mysql php-xdebug php-curl php-zip php-xml php-mbstring -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
COPY config/20-xdebug.ini /etc/php/8.2/apache2/conf.d/

CMD while : ; do sleep 1000; done
EOF;

        $this->dockerFile
            ->setUpdate()
            ->setUpgrade()
            ->setFullPhpApacheDev();

        $this->assertSame($expectedString, $this->dockerFile->getString());
    }

    public function testExplainUpdateAndUpgrade(): void
    {
        $this->dockerFile
            ->setUpdate()
            ->setUpgrade();

        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";
        $expectedExplanation .= "It also perform an update in the operational system repository, so packages can be installed through default operating system utility.\n";
        $expectedExplanation .= "Will update operating system packages.";

        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }

    public function testExplainWithFullPhpApacheDev(): void
    {
        $expectedExplanation = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.\n";   
        $expectedExplanation .= "Will prepare commons php applications for development porpouse, including the Apache web server and Composer.";
        $this->dockerFile->setFullPhpApacheDev();
        $this->assertSame($expectedExplanation, $this->dockerFile->explain());
    }
}
