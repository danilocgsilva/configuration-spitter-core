<?php

declare(strict_types=1);

namespace Tests\Unit\Receipt;

use PHPUnit\Framework\TestCase;
use Danilocgsilva\ConfigurationSpitter\Receipt\NodeReceipt;
use Exception;

class NodeReceiptTest extends TestCase
{
    private NodeReceipt $nodeReceipt;

    public function setUp(): void
    {
        $this->nodeReceipt = new NodeReceipt();
    }

    public function testGet(): void
    {
        $expectedFileData = <<<EOF
services:
  node:
    image: 'node:latest'

EOF;
        $expectedArray = [
            'docker-compose.yml' => $expectedFileData
        ];

        $this->assertSame($expectedArray, $this->nodeReceipt->get());
    }

    public function testAddingNotExistingParameter(): void
    {
        $this->expectException(Exception::class);
        $this->nodeReceipt->setProperty("ThisPropertyDoesNotExists");
    }

    public function testGetParameters()
    {
        $parameters = $this->nodeReceipt->getParameters();
        $expectedParameter = "container-name";
        $this->assertSame($expectedParameter, $parameters[0]);
    }

    public function testExplain(): void
    {
        $expectedExplanation = "Creates a basic node receipt.";
        $this->assertSame($expectedExplanation, $this->nodeReceipt->explain());
    }

    public function testGetName(): void
    {
        $expectedValue = "Node";
        $this->assertSame($expectedValue, $this->nodeReceipt->getName());
    }
}
