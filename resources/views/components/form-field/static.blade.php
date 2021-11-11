<x-form-group
    :inline="data_get($options, 'wrapper.inline', false)">
    @if ($options['label_show'])
        {!! Form::label($options['label_for'], $options['label'], $options['label_attr']) !!}
    @endif

    {!! Html::tag($options['tag'], $options['value'] ?? '', array_merge(compact('name'), $options['attr'])) !!}
</x-form-group>
