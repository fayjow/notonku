<?php

namespace App\Enums;

enum ContentStatus: string
{
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Upcoming = 'upcoming';
    case Hiatus = 'hiatus';
    case Cancelled = 'cancelled';
}
