@php
    $className = data_get($options, 'class');
    $options = array_except($options, 'class');
    $variant = blank($variant) ? 'secondary' : $variant;
    $options = array_merge($options, compact('type'));
@endphp
@can("{$action}_{$sectionCode}")
{!! Form::{$btnType}($text, array_merge(['class' => array_css_class(['btn', "btn-{$variant}", $className])], $options)) !!}
@endcan
