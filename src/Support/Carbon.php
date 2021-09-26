<?php

namespace Vnnit\Core\Support;

use Illuminate\Support\Carbon as LaravelCarbon;

class Carbon extends LaravelCarbon
{
    public static function createNextDay($date, $tz = null)
    {
        return (new static($date, $tz))->modify('+1 day');
    }

    public function isAllZeroDate()
    {
        // Workaround to check date is 0000-00-00 00:00:00
        // because Carbon parses that date as -0001-11-30 00:00:00
        return $this->lte(new Carbon('0001-00-00 00:00:00'));
    }

    public static function createFromFormat($format, $time, $tz = null)
    {
        if (ends_with($format, '-m')) {
            $date_parse = date_parse_from_format($format, $time);
            return parent::createFromDate($date_parse['year'], $date_parse['month'], 1, $tz);
        }
        return parent::createFromFormat($format, $time, $tz);
    }
}
