<?php

use Illuminate\Support\Arr;
use Vnnit\Core\Forms\Form;

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
                array_push($attr, sprintf('%s="%s"', $key, $value));
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

if (! function_exists('avatar_url')) {
    /**
     * Returns the avatar URL of a user.
     *
     * @param $user
     * @return string
     */
    function avatar_url($user)
    {
        $firstLetter = $user->getAttribute('name') ? mb_substr($user->name, 0, 1, 'UTF-8') : 'A';
        $placeholder = 'https://via.placeholder.com/35x35/00a65a/ffffff/&text='.$firstLetter;

        switch (config('vnnit.avatar_type')) {
            case 'gravatar':
                if (!blank(user_get('email'))) {
                    return Gravatar::fallback('https://via.placeholder.com/160x160/00a65a/ffffff/&text='.$firstLetter)->get($user->email);
                } else {
                    return $placeholder;
                }
                break;

            case 'placehold':
                return $placeholder;
                break;

            default:
                return method_exists($user, config('backpack.base.avatar_type')) ? $user->{config('backpack.base.avatar_type')}() : $user->{config('backpack.base.avatar_type')};
                break;
        }
    }
}

if (!function_exists('ifnull')) {
    function ifnull($data, $value) {
        return is_null($data) ? $value : $data;
    }
}

if (!function_exists('form')) {

    function form(Form $form, array $options = [])
    {
        return $form->renderForm($options);
    }
}

if (!function_exists('size_format')) {
    function size_format($size, $format = null) {
        $bytes = sprintf('%u', $size);
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $out = 'B';
        if (is_null($format)) {
            foreach($units as $key => $value) {
                $diff = pow(1024, $key);
                if ($size > $diff) {
                    $out = $value;
                    $bytes = round($size / $diff, 2);
                } else break;
            }
        } else {
            $diff = pow(1024, array_search($format, $units));
            $out = $format;
            $bytes = round($size / $diff, 2);
        }
        $round = round(fmod($bytes, 2));
        return sprintf("%.{$round}f %s", $bytes, $out);
    }
}

if (!function_exists('disk_free_info')) {
    function disk_free_info($directory = '/', $format = null) {
        $free_disk = disk_free_space($directory);
        return size_format($free_disk, $format);
    }
}

if (!function_exists('disk_total_info')) {
    function disk_total_info($directory = '/', $format = null) {
        $total_disk = disk_total_space($directory);
        return size_format($total_disk, $format);
    }
}
