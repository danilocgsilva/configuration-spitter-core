<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

class DockerFile implements SpitterInterface
{
    private bool $update = false;

    private bool $upgrade = false;

    private bool $mariadbClient = false;
    
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

    public function explain(): string
    {
        return "Creates a container based on the slim version of the Debian Bookworm that sleep indefinitely. Good for debugging, development or as resource placeholser";
    }
}
