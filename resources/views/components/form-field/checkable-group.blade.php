<x-form-group
    :inline="data_get($options, 'wrapper.inline', false)">
    @if ($options['label_show'])
        {!! Form::label($options['label_for'], $options['label'], $options['label_attr']) !!}
    @endif

    <div class="custom-{{$type}}-group d-flex align-items-center">
    @foreach ($options['choices'] as $key => $display)
        <div class="{{data_get($options, 'wrapper_attr.class')}} custom-{{$type}}">
            {!! Form::$type($name, $key, data_get($options, 'checked', false), array_merge($options['attr'], ['id' => 'checkable_'.$key])) !!}
            {!! Form::label('checkable_'.$key, $display, $options['checkable_label_attr']) !!}
        </div>
    @endforeach
    </div>
</x-form-group>
