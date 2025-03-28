<?php

enum Statut: string
{
    case ACTIF = 'Actif';
    case BLESSE = 'Blessé';
    case SUSPENDU = 'Suspendu';
    case ABSENT = 'Absent';

    public static function existFromName(string $name): bool
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return true;
            }
        }
        return false; // Return null if no match is found
    }

    public static function fromName(string $name): ?Statut
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        return null;
    }
}