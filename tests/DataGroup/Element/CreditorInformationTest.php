<?php

namespace Sprain\SwissQrBill\Tests\DataGroup\Element;

use PHPUnit\Framework\TestCase;
use Sprain\SwissQrBill\DataGroup\Element\CreditorInformation;

class CreditorInformationTest extends TestCase
{
    /**
     * @dataProvider ibanProvider
     */
    public function testIban($numberOfViolations, $value)
    {
        $creditorInformation = CreditorInformation::create(
            $value
        );

        $this->assertSame($numberOfViolations, $creditorInformation->getViolations()->count());
    }

    public function ibanProvider()
    {
        return [
            [0, 'CH93 0076 2011 6238 5295 7'],
            [0, 'CH9300762011623852957'],
            [0, 'LI21 0881 0000 2324 013A A'],
            [0, 'LI21088100002324013AA'],

            // QR-IBANs
            [0, 'CH44 3199 9123 0008 8901 2'],
            [0, 'CH4431999123000889012'],

            // missing number at end
            [1, 'CH93 0076 2011 6238 5295'],
            [1, 'CH930076201162385295'],
            [1, 'LI21 0881 0000 2324 013A'],
            [1, 'LI21088100002324013A'],

            // missing letter in front
            [2, 'H93 0076 2011 6238 5295'],
            [2, 'H930076201162385295'],
            [2, 'I21 0881 0000 2324 013A'],
            [2, 'I21088100002324013A'],

            // valid IBANs from unsupported countries
            [1, 'AT61 1904 3002 3457 3201'],
            [1, 'NO9386011117947'],

            // random strings
            [2, 'foo'],
            [2, '123'],
            [2, '*'],
            [1, '']
        ];
    }

    /**
     * @dataProvider qrIbanCheckProvider
     */
    public function testContainsQrIban($isQrIban, $value)
    {
        $creditorInformation = CreditorInformation::create(
            $value
        );

        $this->assertSame($isQrIban, $creditorInformation->containsQrIban());
    }

    public function qrIbanCheckProvider()
    {
        return [
            // normal valid IBANs
            [false, 'CH9300762011623852957'],
            [false, 'LI21088100002324013AA'],

            // invalid or unsupported IBANs
            [false, 'AT61 1904 3002 3457 3201'],
            [false, ''],

            // QR-IBANs
            [true, 'CH44 3199 9123 0008 8901 2'],
            [true, 'CH4431999123000889012'],
        ];
    }
}