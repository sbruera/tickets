<?php

namespace App\Enums;

enum TransportMode: string
{
    case AIR = 'air';
    case LAND = 'land';
    case SEA = 'sea';
}