<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

class DockerFile implements SpitterInterface
{
    private bool $update = false;

    private bool $upgrade = false;
    
    public function getString(): string
    {
        $string = "FROM debian:bookworm-slim\n";

        if ($this->update) {
            $string .= "\nRUN apt-get update\n";
        }

        if ($this->upgrade) {
            $string .= "RUN apt-get upgrade -y\n";
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
}
