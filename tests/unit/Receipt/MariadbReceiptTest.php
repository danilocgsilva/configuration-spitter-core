<?php

declare(strict_types=1);

namespace Tests\Unit\Receipt;

use Danilocgsilva\ConfigurationSpitter\Receipt\MariadbReceipt;
use PHPUnit\Framework\TestCase;

class MariadbReceiptTest extends TestCase
{
    private MariadbReceipt $mariadbReceipt;

    public function setUp(): void
    {
        $this->mariadbReceipt = new MariadbReceipt();
    }
    
    public function testGet(): void
    {
        $expectedFileData = <<<EOF
services:
  mariadb:
    image: 'mariadb:latest'
    environment:
      MARIADB_ROOT_PASSWORD: ''

EOF;
        $expectedArray = [
            'docker-compose.yml' => $expectedFileData
        ];

        $this->assertSame($expectedArray, $this->mariadbReceipt->get());
    }
}
