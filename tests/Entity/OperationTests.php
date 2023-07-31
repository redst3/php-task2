<?php
declare(strict_types=1);

namespace Task2\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Task2\Entity\Operation;

class OperationTests extends TestCase{
    public function testGetCountryCodeByBin(): void
    {
        $operation = new Operation(
            '45717360',
            100.00,
            'EUR'
        );
        $this->assertEquals('DK', $operation->getCountryCodeByBin());
    }
}