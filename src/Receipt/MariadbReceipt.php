<?php

declare(strict_types=1);

namespace Danilocgsilva\ConfigurationSpitter\Receipt;

class MariadbReceipt implements ReceiptInterface
{
    public function explain(): string
    {
        return "Raise a mariadb service.";
    }
}
