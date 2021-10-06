@php
    $options = $attributes->class(['form-control', $class])->getAttributes();
    $prefix = config('vnnit-core.prefix');
@endphp
<div class="{{$groupClass}}">
@if (!empty($icon))
<div class="input-group">
    @if ($prepent)
    <div class="input-group-prepend">{!! $icon !!}</div>
    @endif
@endif

    {!! Form::input($type, $name, $value, $options) !!}

    @include($prefix.'::components.forms.help-block')

    @include($prefix.'::components.forms.errors')

@if (!empty($icon))
    @if (!$prepent)
    <div class="input-group-append">{!! $icon !!}</div>
    @endif
</div>
@endif
</div>
