<x-form-group
    :inline="data_get($options, 'wrapper.inline', false)">
    @if ($options['label_show'])
        {!! Form::label($options['label_for'], $options['label'], $options['label_attr']) !!}
    @endif

    {!! Form::{$type}($name, $options['value'], $options['checked'], $options['attr']) !!}
</x-form-group>
