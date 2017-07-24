<?php

namespace NifValidator;

class NifValidator
{
    const NIE_TYPES = 'XYZ';
    const DNINIE_CHECK_TABLE = 'TRWAGMYFPDXBNJZSQVHLCKE';

    const NIF_TYPES_WITH_LETTER_CHECK = 'PQSW';
    const NIF_TYPES_WITH_NUMBER_CHECK = 'ABEH';

    const NIF_LETTER_CHECK_TABLE = 'JABCDEFGHI';

    const DNI_REGEX = '#^(?<dni>[0-9]{8})(?<check>[A-Z])$#';
    const NIE_REGEX = '#^(?<nie_type>['. self::NIE_TYPES .'])(?<nie>[0-9]{7})(?<check>[A-Z])$#';
    const OTHER_NIF_REGEX = '#^(?<nif_type>[ABCDEFGHJKLMNPQRSUVW])(?<nif>[0-9]{7})(?<check>[0-9A-Z])$#';

    /**
     * Validate Spanish NIFS
     * Input is not uppercased, or stripped of any incorrect characters
     */
    public static function isValid(string $nif): bool
    {
        return self::isValidDni($nif) || self::isValidNie($nif) || self::isValidOtherNif($nif);
    }

    /**
     * DNI validation is pretty straight forward.
     * Just mod 23 the 8 digit number and compare it to the check table
     */
    private static function isValidDni(string $dni): bool
    {
        if (!preg_match(self::DNI_REGEX, $dni, $matches)) {
            return false;
        }

        return self::DNINIE_CHECK_TABLE[$matches['dni'] % 23] === $matches['check'];
    }

    /**
     * NIE validation is similar to the DNI.
     * The first letter needs an equivalent number before the mod operation
     */
    private static function isValidNie(string $nie): bool
    {
        if (!preg_match(self::NIE_REGEX, $nie, $matches)) {
            return false;
        }

        $nieType = strpos(self::NIE_TYPES, $matches['nie_type']);
        $nie = $nieType . $matches['nie'];

        return self::DNINIE_CHECK_TABLE[$nie % 23] === $matches['check'];
    }

    /**
     * Other NIFS and CIFS get more complicated. See references
     *
     * @see https://es.wikipedia.org/wiki/N%C3%BAmero_de_identificaci%C3%B3n_fiscal
     * @see https://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
     */
    private static function isValidOtherNif(string $nif): bool
    {
        if (!preg_match(self::OTHER_NIF_REGEX, $nif, $matches)) {
            return false;
        }

        $split = str_split($matches['nif']);

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
        if (self::nifHasLetterCheck($matches['nif_type'], $nif)) {
            return self::checkNifLetter($calculatedCheckDigit, $matches['check']);
        }

        //Nifs with only numbers
        if (self::nifHasNumberCheck($matches['nif_type'], $nif)) {
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