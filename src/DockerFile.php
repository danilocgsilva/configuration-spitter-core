<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

class DockerFile implements SpitterInterface
{
    private bool $update = false;

    private bool $upgrade = false;

    private bool $mariadbClient = false;

    private bool $mariadbServer = false;
    
    public function getString(): string
    {
        $string = "FROM debian:bookworm-slim\n";

        if ($this->update) {
            $string .= "\nRUN apt-get update\n";
        }

        if ($this->upgrade) {
            $string .= "RUN apt-get upgrade -y\n";
        }

        if ($this->mariadbClient) {
            $string .= "RUN apt-get install mariadb-client -y\n";
        }

        if ($this->mariadbServer) {
            $string .= "RUN apt-get install mariadb-server -y\n";
        }

        $string .= "\nCMD while : ; do sleep 1000; done";

        return $string;
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
}
