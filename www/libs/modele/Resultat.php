<?php

enum Resultat: string
{
    case VICTOIRE = 'Victoire';
    case DEFAITE = 'Defaite';
    case NUL = 'Nul';

    public static function existFromName(string $name): bool
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return true;
            }
        }
        return false; // Return null if no match is found
    }

    public static function fromName(string $name): ?Resultat
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        return null;
    }
}