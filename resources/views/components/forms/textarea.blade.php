@php
    $options = $attributes->class(['form-control', $class])->getAttributes();
@endphp
<div class="{{$groupClass}}">
    {!! Form::textarea($name, $value, $options) !!}

    @include('components.forms.help-block')

    @include('components.forms.errors')
</div>
