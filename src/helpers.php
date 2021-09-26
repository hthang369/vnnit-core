<?php

use Illuminate\Support\Arr;

if (!function_exists('get_classes')) {
    function get_classes($classes)
    {
        $result = [];
        $spacer = ' ';

        foreach ($classes as $class) {
            if (isset($class['class']) && !empty($class['class'])) {
                array_push($result, $class['class']);
            } elseif (!empty($class) && !is_array($class)) {
                array_push($result, $class);
            }
        }

        return implode($spacer, $result);
    }
}

if (!function_exists('translate')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function translate($key = null, $replace = [], $locale = null)
    {
        $prefix = config('vnnit-core.prefix');
        return trans($prefix . '::' . $key, $replace, $locale);
    }
}

if (!function_exists('attributes_get')) {
    function attributes_get($attributes)
    {
        $attr = [];
        foreach ($attributes as $key => $value) {
            if (is_string($key))
                array_push($attr, sprintf('%s=%s', $key, $value));
            else
                array_push($attr, $value);
        }

        return join(' ', $attr);
    }
}

if (!function_exists('array_css_class')) {
    function array_css_class($arrClass)
    {
        return Arr::toCssClasses($arrClass);
    }
}
