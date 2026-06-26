<?php

namespace App\Twig;

use App\Util\FrenchDate;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('month_year_fr', [FrenchDate::class, 'monthYear']),
        ];
    }
}
