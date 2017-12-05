<?php

namespace App\Infrastructure\Twig;

use Cake\Chronos\Chronos;
use DateTimeInterface;
use \Twig_Extension;
use \Twig_Filter;

class TimeExtension extends Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_Filter(
                'ago',
                function (DateTimeInterface $dateTime) {
                    return Chronos::instance($dateTime)->diffForHumans();
                }
            ),
        ];
    }
}
