<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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

if (!function_exists('user_get')) {
    function user_get($key = null)
    {
        $user = null;
        if (Auth::check()) {
            $user = Auth::user();
        }
        if (!is_null($key) && !is_null($user)) {
            return object_get($user, $key);
        }
        return $user;
    }
}

if (!function_exists('user_can')) {
    function user_can($action)
    {
        return (Auth::check() && Auth::user()->can($action));
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

if (!function_exists('vnn_asset')) {
    function vnn_asset($path, $secure = null) {
        $assetPath = config('filesystems.disks.public.asset_path');
        $pathImage = asset($assetPath.$path, $secure);
        return $pathImage;
    }
}

if (!function_exists('storage_asset')) {
    function storage_asset($path, $secure = null) {
        $pathImage = vnn_asset($path, $secure);
        if (!file_exists($pathImage)) {
            $pathImage = vnn_asset(config('filesystems.disks.public.path_image').$path, $secure);
        }
        return $pathImage;
    }
}

if (! function_exists('array_merge_recursive_simple')) {
    function array_merge_recursive_simple(array &$array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_string($key)) {
                if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                    $merged[$key] = array_merge_recursive_simple($merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            } else {
                $merged[] = $value;
            }
        }

        return $merged;
    }
}

if (!function_exists('laka_link_method')) {
    function laka_link_method($method, $link, $title = null, $variant = null, $parameters = [], $attributes = [], $action = null, $sectionCode = null, $secure = null, $escape = true)
    {
        $icon = data_get($attributes, 'icon');
        $content = $title;
        if (!blank($icon)) {
            $content = "<i class='fa {$icon} mr-1'></i>".$title;
            $escape = false;
        }
        $variant = $variant ?? 'secondary';
        $confirmMsg = data_get($attributes, 'data-confirmation-msg');
        $classAttrs = array_unique(array_merge(['btn', "btn-{$variant}"], explode(' ', data_get($attributes, 'class', ''))));
        $attributesNew = array_add(array_except($attributes, ['icon', 'class', 'data-confirmation-msg']), 'class', array_css_class($classAttrs));
        if ($confirmMsg)
            data_set($attributesNew, 'onclick', "return confirm('$confirmMsg')");

        if (str_is($method, 'link'))
            return app('html')->{$method}($link, $content, $attributesNew, $secure, $escape);

        if (!blank($action) && !blank($sectionCode)) {
            if (user_can("{$action}_{$sectionCode}")) {
                return app('html')->{$method}($link, $content, $parameters, $attributesNew, $secure, $escape);
            } else {
                return '';
            }
        }

        return app('html')->{$method}($link, $content, $parameters, $attributesNew, $secure, $escape);
    }
}

if (!function_exists('bt_link_to')) {
    function bt_link_to($url, $title = null, $variant = null, $attributes = [], $action = null, $sectionCode = null, $secure = null, $escape = true)
    {
        return laka_link_method('link', $url, $title, $variant, [], $attributes, $action, $sectionCode, $secure, $escape);
    }
}

if (!function_exists('bt_link_to_route')) {
    function bt_link_to_route($name, $title = null, $variant = null, $parameters = [], $attributes = [], $action = null, $sectionCode = null, $secure = null, $escape = true)
    {
        return laka_link_method('linkRoute', $name, $title, $variant, $parameters, $attributes, $action, $sectionCode, $secure, $escape);
    }
}

if (!function_exists('bt_link_to_action')) {
    function bt_link_to_action($action, $title = null, $variant = null, $parameters = [], $attributes = [], $actionName = null, $sectionCode = null, $secure = null, $escape = true)
    {
        return laka_link_method('linkAction', $action, $title, $variant, $parameters, $attributes, $actionName, $sectionCode, $secure, $escape);
    }
}

if (!function_exists('convert_k_to_c')) {
    function convert_k_to_c($number) {
        return round($number - 273.15);
    }
}

if (!function_exists('convert_f_to_c')) {
    function convert_f_to_c($number) {
        return round(($number - 32) / 1.8);
    }
}
