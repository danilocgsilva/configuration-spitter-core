<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

class DockerFile implements SpitterInterface
{
    public function getString(): string
    {
        return <<<EOF
FROM debian:bookworm-slim

CMD while : ; do sleep 1000; done
EOF;
    }
}
