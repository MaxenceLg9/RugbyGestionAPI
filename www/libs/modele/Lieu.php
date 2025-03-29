<?php

enum Lieu: string
{
    case DOMICILE = 'Domicile';
    case EXTERIEUR = 'Exterieur';
    public static function existFromName(string $name): bool
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return true;
            }
        }
        return false; // Return null if no match is found
    }

    public static function staticCases() : array{
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->name] = $case->value;
        }
        return $cases;
    }
}