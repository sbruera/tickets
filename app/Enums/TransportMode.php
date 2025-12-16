<?php

namespace App\Enums;

enum TransportMode: string
{
    case AI = 'ai';
    case LAND = 'land';
    case SEA = 'sea';
}