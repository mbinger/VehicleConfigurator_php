<?php

namespace App\Kfz;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class Text
{
    private const CarbonDateFormat = "CarbonDateFormat";

    public static function formatDate($date)
    {
        if (Lang::has(Text::CarbonDateFormat, App::getLocale(), false))
        {
            return Carbon::create($date)->format(__(Text::CarbonDateFormat));
        }
        else
        {
            return Carbon::create($date)->toDateString();
        }
    }

    public static function parseDate($dateStr)
    {
        if (Lang::has(Text::CarbonDateFormat, App::getLocale(), false))
        {
            return Carbon::createFromFormat(__(Text::CarbonDateFormat), $dateStr);
        }
        else
        {
             return Carbon::create($dateStr);
        }
    }
}
