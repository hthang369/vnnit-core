<x-form-group
    :inline="data_get($options, 'wrapper.inline', false)">
    {!! Form::button($options['label'], array_merge(compact('type', 'name'), $options['attr'])) !!}
</x-form-group>
