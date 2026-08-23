<?php

namespace App\Enums;

enum ContentType: string
{
    case Movie = 'movie';
    case Series = 'series';
    case Anime = 'anime';
    case Donghua = 'donghua';
}
