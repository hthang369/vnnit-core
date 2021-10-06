@php
    $className = data_get($options, 'class');
    $options = array_except($options, 'class');
@endphp
{!! Form::input($type, $name, $value, array_merge(['class' => array_css_class(['form-control', $className])], $options)) !!}
