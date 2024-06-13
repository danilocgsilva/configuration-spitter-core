<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

class DockerFile implements SpitterInterface
{
    private bool $update = false;

    private bool $upgrade = false;

    private bool $mariadbClient = false;

    private bool $mysql = false;

    private bool $mariadbServer = false;
    
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

    public function explain(): string
    {   $baseExplainString = "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholder.";
        if ($this->update) {
            $baseExplainString .= "\nIt also perform an update in the operational system repository, so packages can be installed through default operating system utility.";
        }
        if ($this->upgrade) {
            $baseExplainString .= "\nWill update operating system packages.";   
        }
        
        return $baseExplainString;
    }

    public function setMysql()
    {
        $this->mysql = true;
    }
}
