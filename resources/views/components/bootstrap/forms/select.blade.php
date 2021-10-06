@php
    $className = data_get($attributes, 'class');
    $attributes = array_except($attributes, 'class');
@endphp
{!! Form::select($name, $list, $selected, array_merge(['class' => array_css_class(['form-control', $className])], $attributes), $options) !!}
