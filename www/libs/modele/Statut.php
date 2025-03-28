<?php

enum Statut: string
{
    case ACTIF = 'ACTIF';
    case BLESSE = 'BLESSE';
    case SUSPENDU = 'SUSPENDU';
    case ABSENT = 'ABSENT';

    public static function existFrom(string $name): bool
    {
        return self::tryFrom($name) != null; // Return null if no match is found
    }
}