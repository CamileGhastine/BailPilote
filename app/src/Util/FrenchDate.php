<?php

namespace App\Util;

final class FrenchDate
{
    private const MONTHS = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre',
    ];

    public static function monthYear(\DateTimeInterface $date): string
    {
        return self::MONTHS[(int) $date->format('n')] . ' ' . $date->format('Y');
    }
}
