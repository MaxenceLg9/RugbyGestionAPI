<?php

namespace libs\modele;
enum Resultat: string
{
    case VICTOIRE = 'VICTOIRE';
    case DEFAITE = 'DEFAITE';
    case NUL = 'NUL';

    public static function existFrom(string $name): bool
    {
        return self::tryFrom($name) != null; // Return null if no match is found
    }
}