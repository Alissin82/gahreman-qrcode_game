<?php

namespace App\Enums;

use App\Traits\CleanEnum;

enum ActionStatus:string
{
    use CleanEnum;
    case Pending = 'Pending';
    case Completed = 'Completed';
}
