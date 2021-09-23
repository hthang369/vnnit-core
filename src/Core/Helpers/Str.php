<?php

namespace Vnnit\Core\Helpers;

class Str
{
    public static function multibyteTrim($str)
    {
        return preg_replace("/(^[\s　]+)|([\s　]+$)/us", '', $str);
    }
}
