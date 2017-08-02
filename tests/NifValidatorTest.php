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
     * @dataProvider validDnis
     */     
    public function testValidDni(string $nif)
    {
        self::assertTrue(NifValidator::isValidDni($nif));
    }

    /**
     * @dataProvider invalidDnis
     */     
    public function testInvalidDni(string $nif)
    {
        self::assertFalse(NifValidator::isValidDni($nif));
    }

    /**
     * @dataProvider validNies
     */     
    public function testValidNie(string $nif)
    {
        self::assertTrue(NifValidator::isValidNie($nif));
    }

    /**
     * @dataProvider invalidNies
     */     
    public function testInvalidNie(string $nif)
    {
        self::assertFalse(NifValidator::isValidNie($nif));
    }

    /**
     * @dataProvider validOtherNifs
     */     
    public function testValidOtherNif(string $nif)
    {
        self::assertTrue(NifValidator::isValidOtherPersonalNif($nif));
    }

    /**
     * @dataProvider invalidOtherNifs
     */     
    public function testInvalidOtherNif(string $nif)
    {
        self::assertFalse(NifValidator::isValidOtherPersonalNif($nif));
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

    /**
     * @dataProvider validEntityNifs
     */
    public function testValidCif(string $nif)
    {
        self::assertTrue(NifValidator::isValidCif($nif));
    }

    /**
     * @dataProvider invalidEntityNifs
     * @dataProvider validPersonalNifs
     */
    public function testInvalidCif(string $nif)
    {
        self::assertFalse(NifValidator::isValidCif($nif));
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

    public function validDnis()
    {
        return [
            //DNI
            ['93471790C'],
            ['43596386R'],
            ['00000010X'],
        ];        
    }

    public function invalidDnis()
    {
        return [
            //DNI
            ['93471790A'],
            ['43596386B'],
            ['00000010Y'],
        ];        
    }

    public function validNies()
    {
        return [
            //NIE
            ['X5102754C'],
            ['Z8327649K'],
            ['Y4174455S'],
        ];
    }

    public function invalidNies()
    {
        //NIE
        return [
            ['X5102754A'],
            ['Z8327649B'],
            ['Y4174455C'],        
        ];
    }

    public function validOtherNifs()
    {
        return [
            //Other NIF
            ['K9514336H'],            
        ];
    }

    public function invalidOtherNifs()
    {
        //Other NIF
        return [
            ['M3118299M'],                
        ];
    }

    public function validPersonalNifs()    
    {        
        return array_merge($this->validDnis(), $this->validNies(), $this->validOtherNifs());
    }

    public function invalidPersonalNifs()
    {
        return array_merge($this->invalidDnis(), $this->invalidNies(), $this->invalidOtherNifs());
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
