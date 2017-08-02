<?php

namespace NifValidator;

class NifValidator
{
    const NIE_TYPES = 'XYZ';
    const DNINIE_CHECK_TABLE = 'TRWAGMYFPDXBNJZSQVHLCKE';

    const NIF_TYPES_WITH_LETTER_CHECK = 'PQSW';
    const NIF_TYPES_WITH_NUMBER_CHECK = 'ABEH';

    const NIF_LETTER_CHECK_TABLE = 'JABCDEFGHI';

    const DNI_REGEX = '#^(?<number>[0-9]{8})(?<check>[A-Z])$#';
    const NIE_REGEX = '#^(?<type>['. self::NIE_TYPES .'])(?<number>[0-9]{7})(?<check>[A-Z])$#';
    const OTHER_PERSONAL_NIF_REGEX = '#^(?<type>[KLM])(?<number>[0-9]{7})(?<check>[0-9A-Z])$#';
    const CIF_REGEX = '#^(?<type>[ABCDEFGHJNPQRSUVW])(?<number>[0-9]{7})(?<check>[0-9A-Z])$#';

    /**
     * Validate Spanish NIFS
     * Input is not uppercased, or stripped of any incorrect characters
     */
    public static function isValid(string $nif): bool
    {
        return self::isValidDni($nif) || self::isValidNie($nif) || self::isValidCif($nif) || self::isValidOtherPersonalNif($nif);
    }

    /**
     * Validate Spanish NIFS given to persons
     */
    public static function isValidPersonal(string $nif): bool
    {
        return self::isValidDni($nif) || self::isValidNie($nif) || self::isValidOtherPersonalNif($nif);
    }

    /**
     * Validate Spanish NIFS given to non-personal entities (e.g. companies, public corporations, ngos...)
     */
    public static function isValidEntity(string $nif): bool
    {
        return self::isValidCif($nif);
    }

    /**
     * DNI validation is pretty straight forward.
     * Just mod 23 the 8 digit number and compare it to the check table
     */
    public static function isValidDni(string $dni): bool
    {
        if (!preg_match(self::DNI_REGEX, $dni, $matches)) {
            return false;
        }
        if ('00000000' === $matches['number']) {
            return false;
        }

        return self::DNINIE_CHECK_TABLE[$matches['number'] % 23] === $matches['check'];
    }

    /**
     * NIE validation is similar to the DNI.
     * The first letter needs an equivalent number before the mod operation
     */
    public static function isValidNie(string $nie): bool
    {
        if (!preg_match(self::NIE_REGEX, $nie, $matches)) {
            return false;
        }

        $nieType = strpos(self::NIE_TYPES, $matches['type']);
        $nie = $nieType . $matches['number'];

        return self::DNINIE_CHECK_TABLE[$nie % 23] === $matches['check'];
    }

    /**
     * Other personal NIFS are meant for temporary residents that do not qualify for a NIE but nonetheless need a NIF
     *
     * See references
     *
     * @see https://es.wikipedia.org/wiki/N%C3%BAmero_de_identificaci%C3%B3n_fiscal
     * @see https://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
     */
    public static function isValidOtherPersonalNif(string $nif): bool
    {
        if (!preg_match(self::OTHER_PERSONAL_NIF_REGEX, $nif, $matches)) {
            return false;
        }

        return self::isValidNifCheck($nif, $matches);
    }

    /**
     * CIFS are only meant for non-personal entities
     *
     * See references
     *
     * @see https://es.wikipedia.org/wiki/N%C3%BAmero_de_identificaci%C3%B3n_fiscal
     * @see https://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
     */
    public static function isValidCif(string $cif): bool
    {
        if (!preg_match(self::CIF_REGEX, $cif, $matches)) {
            return false;
        }

        return self::isValidNifCheck($cif, $matches);
    }

    private static function isValidNifCheck(string $nif, array $matches): bool
    {
        $split = str_split($matches['number']);

        $even = array_filter($split, function($key) {
            return $key & 1;
        }, ARRAY_FILTER_USE_KEY);
        $sumEven = array_sum($even);

        $odd = array_filter($split, function($key) {
            return !($key & 1);
        }, ARRAY_FILTER_USE_KEY);
        $sumOdd = array_reduce($odd, function($carry, $item) {
            return $carry + array_sum(str_split($item * 2));
        });

        $calculatedCheckDigit = (10 - ($sumEven + $sumOdd) % 10) % 10;

        //Nifs with only letters
        if (self::nifHasLetterCheck($matches['type'], $nif)) {
            return self::checkNifLetter($calculatedCheckDigit, $matches['check']);
        }

        //Nifs with only numbers
        if (self::nifHasNumberCheck($matches['type'], $nif)) {
            return self::checkNifNumber($calculatedCheckDigit, $matches['check']);
        }

        //Nifs that accept both
        return
            self::checkNifLetter($calculatedCheckDigit, $matches['check'])
            || self::checkNifNumber($calculatedCheckDigit, $matches['check'])
            ;
    }

    private static function nifHasLetterCheck(string $nifType, string $nif): bool
    {
        return
            false !== strpos(self::NIF_TYPES_WITH_LETTER_CHECK, $nifType)
            || ('0' === $nif[0] && '0' === $nif[1])
            ;
    }

    private static function checkNifLetter(int $calculatedCheckDigit, string $checkDigit): bool
    {
        return self::NIF_LETTER_CHECK_TABLE[$calculatedCheckDigit] === $checkDigit;
    }

    private static function nifHasNumberCheck(string $nifType, string $nif): bool
    {
        return false !== strpos(self::NIF_TYPES_WITH_NUMBER_CHECK, $nifType);
    }

    private static function checkNifNumber(string $calculatedCheckDigit, string $checkDigit): bool
    {
        return $calculatedCheckDigit === $checkDigit;
    }
}
