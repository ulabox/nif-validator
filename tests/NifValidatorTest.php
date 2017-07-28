<?php

namespace NifValidator;

use PHPUnit\Framework\TestCase;

class NifValidatorTest extends TestCase
{
    /**
     * @dataProvider validPersonalNifs
     * @dataProvider validEntityNifs
     */
    public function testValidNif(string $nif)
    {
        self::assertTrue(NifValidator::isValid($nif));
    }

    /**
     * @dataProvider invalidNifs
     * @dataProvider invalidPersonalNifs
     * @dataProvider invalidEntityNifs
     */
    public function testInvalidNif(string $nif)
    {
        self::assertFalse(NifValidator::isValid($nif));
    }

    /**
     * @dataProvider validPersonalNifs
     */
    public function testValidPersonalNif(string $nif)
    {
        self::assertTrue(NifValidator::isValidPersonal($nif));
    }

    /**
     * @dataProvider invalidPersonalNifs
     * @dataProvider validEntityNifs
     */
    public function testInvalidPersonalNif(string $nif)
    {
        self::assertFalse(NifValidator::isValidPersonal($nif));
    }

    /**
     * @dataProvider validEntityNifs
     */
    public function testValidEntityNif(string $nif)
    {
        self::assertTrue(NifValidator::isValidEntity($nif));
    }

    /**
     * @dataProvider invalidEntityNifs
     * @dataProvider validPersonalNifs
     */
    public function testInvalidEntityNif(string $nif)
    {
        self::assertFalse(NifValidator::isValidEntity($nif));
    }

    public function invalidNifs()
    {
        return [
            //Garbage
            ['AAAAAAAAA'],
            ['999999999'],
            ['BBBBB'],
            ['1'],
            ['93471790C0'],
            ['00000000T'],
        ];
    }

    public function validPersonalNifs()
    {
        return [
            //DNI
            ['93471790C'],
            ['43596386R'],
            //NIE
            ['X5102754C'],
            ['Z8327649K'],
            ['Y4174455S'],
            //Other NIF
        ];
    }

    public function invalidPersonalNifs()
    {
        return [
            //DNI
            ['93471790A'],
            ['43596386B'],
            //NIE
            ['X5102754A'],
            ['Z8327649B'],
            ['Y4174455C'],
            //Other NIF
        ];
    }

    public function validEntityNifs()
    {
        return [
            //CIF
            ['A58818501'],
            ['B65410011'],
            ['V7565938C'],
            ['V75659383'],
            ['F0605378I'],
            ['Q2238877A'],
            ['D40022956'],
        ];
    }

    public function invalidEntityNifs()
    {
        return [
            //CIF
            ['A5881850B'],
            ['B65410010'],
            ['V75659382'],
            ['V7565938B'],
            ['F06053787'],
            ['Q22388770'],
            ['D4002295J'],
        ];
    }
}
