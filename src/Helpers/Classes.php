<?php

namespace Vnnit\Core\Helpers;

class Classes
{
    public static function get($classes)
    {
        $result = [];
        $spacer = ' ';

        foreach($classes as $class) {
            if (isset($class['class']) && !empty($class['class'])) {
                array_push($result, $class['class']);
            } elseif(!empty($class) && !is_array($class)) {
                array_push($result, $class);
            }
        }

        return join($spacer, $result);
    }
}
