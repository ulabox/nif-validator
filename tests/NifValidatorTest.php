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

    /**
     * @dataProvider validPersonalNifs
     */
    public function testValidPersonalNif(string $nif)
    {
        self::assertTrue(NifValidator::isValidPersonal($nif));
    }

    /**
     * @dataProvider invalidPersonalNifs
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
     */
    public function testInvalidEntityNif(string $nif)
    {
        self::assertFalse(NifValidator::isValidEntity($nif));
    }

    public function validNifs()
    {
        return array_merge(
            $this->validPersonalNifs(),
            $this->validEntityNifs()
        );
    }

    public function invalidNifs()
    {
        return array_merge(
            $this->invalidPersonalNifs(true),
            $this->invalidEntityNifs(true),
            [
                //Garbage
                ['AAAAAAAAA'],
                ['999999999'],
                ['BBBBB'],
                ['1'],
                ['93471790C0'],
            ]
        );
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

    public function invalidPersonalNifs($onlyPersonal = false)
    {
        return array_merge([
            //DNI
            ['93471790A'],
            ['43596386B'],
            //NIE
            ['X5102754A'],
            ['Z8327649B'],
            ['Y4174455C'],
            //Other NIF
        ], true === $onlyPersonal ? [] : $this->validEntityNifs());
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

    public function invalidEntityNifs($onlyEntity = false)
    {
        return array_merge([
            //CIF
            ['B6541001A'],
            ['V75659382'],
            ['F0605378J'],
            ['Q22388772'],
            ['D4002295C'],
        ], true === $onlyEntity ? [] : $this->validPersonalNifs());
    }
}