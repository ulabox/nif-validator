<?php

namespace NifValidator;

use PHPUnit\Framework\TestCase;

class NifValidatorTest extends TestCase
{
    /**
     * @dataProvider validNifs
     */
    public function testValidNif(string $nif)
    {
        self::assertTrue(NifValidator::isValid($nif));
    }

    /**
     * @dataProvider invalidNifs
     */
    public function testInvalidNif(string $nif)
    {
        self::assertFalse(NifValidator::isValid($nif));
    }

    public function validNifs()
    {
        return [
            //DNI
            ['93471790C'],
            ['43596386R'],
            //NIE
            ['X5102754C'],
            ['Z8327649K'],
            ['Y4174455S'],
            //CIF
            ['A58818501'],
            ['B65410011'],
            ['V7565938C'],
            ['V75659383'],
            ['F0605378I'],
            ['Q2238877A'],
            ['D40022956'],
            //Other NIF
        ];
    }

    public function invalidNifs()
    {
        return [
            //DNI
            ['93471790A'],
            ['43596386B'],
            //NIE
            ['X5102754A'],
            ['Z8327649B'],
            ['Y4174455C'],
            //CIF
            ['B6541001A'],
            ['V75659382'],
            ['F0605378J'],
            ['Q22388772'],
            ['D4002295C'],
            //Other NIF

            //Garbage
            ['AAAAAAAAA'],
            ['999999999'],
            ['BBBBB'],
            ['1'],
            ['93471790C0'],
        ];
    }
}