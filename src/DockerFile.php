<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

use Exception;

class DockerFile implements SpitterInterface
{
    private bool $update = false;

    private bool $upgrade = false;

    private bool $mariadbClient = false;

    private bool $mysql = false;

    private bool $mariadbExplanationOff = false;

    private bool $mariadbServer = false;

    private bool $phpApache = false;

    private bool $fullPhpApacheDev = false;
    
    public function getString(): string
    {
        $stringArray = ["FROM debian:bookworm-slim"];
        $stringArray[] = "";

        if ($this->update) {
            $stringArray[] = "RUN apt-get update";
        }
        if ($this->upgrade) {
            $stringArray[] = "RUN apt-get upgrade -y";
        }
        if ($this->mariadbClient) {
            $stringArray[] = "RUN apt-get install mariadb-client -y";
        }
        if ($this->mariadbServer) {
            $stringArray[] = "RUN apt-get install mariadb-server -y";
        }
        if ($this->mysql) {
            $stringArray[] = "RUN apt-get install mysql -y";
        }
        if ($this->phpApache) {
            $stringArray[] = "RUN apt-get install php -y";
        }
        if ($this->fullPhpApacheDev) {
            $stringArray[] = "RUN apt-get install curl git zip -y";
            $stringArray[] = "RUN apt-get install php php-mysql php-xdebug php-curl php-zip php-xml -y";
            $stringArray[] = "RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer";
        }
        if (count($stringArray) > 2) {
            $stringArray[] = "";
        }
        
        $stringArray[] = "CMD while : ; do sleep 1000; done";

        return implode("\n", $stringArray);
    }

    public function setUpdate(): self
    {
        $this->update = true;
        return $this;
    }

    public function setUpgrade(): self
    {
        $this->upgrade = true;
        return $this;
    }

    public function setMariadbClient(): self
    {
        $this->mariadbClient = true;
        return $this;
    }

    public function setMariaDbServer(): self
    {
        $this->mariadbServer = true;
        return $this;
    }

    public function setMysql()
    {
        $this->mysql = true;
    }

    public function disableExplanation(string $explanationService): void
    {
        if ($explanationService === "mariadb") {
            $this->mariadbExplanationOff = true;
        } else {
            throw new Exception("Wrong explanation to disable.");
        }
    }

    public function explain(): string
    {   
        $baseExplainString = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.";
        if ($this->update) {
            $baseExplainString .= "\nIt also perform an update in the operational system repository, so packages can be installed through default operating system utility.";
        }
        if ($this->upgrade) {
            $baseExplainString .= "\nWill update operating system packages.";
        }
        if ($this->mysql) {
            $baseExplainString .= "\nThe container will be shipped with mysql.";
        }
        if ($this->mariadbClient && !$this->mariadbExplanationOff) {
            $baseExplainString .= "\nThe Mariadb client will be added to the container.";
        }
        if ($this->phpApache) {
            $baseExplainString .= "\nInstalls php with Apache together as well.";
        }
        if ($this->fullPhpApacheDev) {
            $baseExplainString .= "\nWill prepare commons php applications for development porpouse, including the Apache web server and Composer.";
        }
        
        return $baseExplainString;
    }

    public function setPhpApache(): self
    {
        $this->phpApache = true;
        return $this;
    }

    public function setFullPhpApacheDev(): self
    {
        $this->fullPhpApacheDev = true;
        return $this;
    }
}
