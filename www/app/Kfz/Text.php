<?php

namespace App\Kfz;

use Carbon\Carbon;

class Text
{
    public const REQUIRED = 'required';

    public static function formatDate($date)
    {
        return Carbon::create($date)->format('d.m.Y');
    }
}
