<?php

namespace App\Enums;


use Modules\Support\Traits\CleanEnum;

enum ActionStatus:string
{
    use CleanEnum;
    case Pending = 'Pending';
    case Completed = 'Completed';
}
