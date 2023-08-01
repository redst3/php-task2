<?php
declare(strict_types=1);

namespace Task2\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Task2\Entity\Operation;

class OperationTest extends TestCase{
    public function testGetCountryCodeByBinOK(): void
    {
        $operation = new Operation(
            '45717360',
            100.00,
            'EUR'
        );
        $this->assertEquals('DK', $operation->getCountryCodeByBin());
    }
    public function testCalculateAndPrintFeeEuropean(): void
    {
        $operation = new Operation(
            '45717360',
            100.00,
            'EUR'
        );
        $operation->setEuropean();
        $this->assertEquals(1, $operation->findExchangeRate());
    }
    public function testCalculateAndPrintNonEuropean(): void
    {
        $operation = new Operation(
            '516793',
            100.00,
            'EUR'
        );
        $this->assertEquals(2, $operation->findExchangeRate());
    }
}