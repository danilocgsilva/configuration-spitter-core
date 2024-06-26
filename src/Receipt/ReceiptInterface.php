<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

interface ReceiptInterface
{
    public function explain(): string;

    public function getParameters(): array;

    public function get(): array;

    public function setProperty(string $command);

    public static function getName(): string;
}
