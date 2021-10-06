<x-form-group
    :inline="data_get($options, 'wapper.inline', false)">
    @if ($options['label_show'])
        {!! Form::label($options['label_for'], $options['label'], $options['label_attr']) !!}
    @endif

    {!! Form::input($type, $name, $options['value'], $options['attr']) !!}
</x-form-group>
