<x-form-group
    :inline="data_get($options, 'wapper.inline', false)">
    {!! Form::button($options['label'], array_merge(compact('type', 'name'), $options['attr'])) !!}
</x-form-group>
