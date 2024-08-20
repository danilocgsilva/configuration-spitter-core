<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter;

class XDebugFileReceipt
{
    private string $pathName = "config/xdebug.ini";

    private string $content = <<<EOF
zend_extension=xdebug.so
xdebug.start_with_request=1
xdebug.mode=debug
EOF;

    public function getPathName(): string
    {
        return $this->pathName;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
