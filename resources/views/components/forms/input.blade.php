@php
    $options = $attributes->class(['form-control', $class])->getAttributes();
@endphp
<div class="{{$groupClass}}">
@if (!empty($icon))
<div class="input-group">
    @if ($prepent)
    <div class="input-group-prepend">{!! $icon !!}</div>
    @endif
@endif

    {!! Form::input($type, $name, $value, $options) !!}

    @if(!empty($help))
        <small id="help-{{ $name }}" class="form-text text-muted">{!! $help !!}</small>
    @endif

    @if(isset($errors) && $errors->has($name))
        <div class="{{ $errors->has($name) ? 'invalid' : '' }}-feedback d-block">
        {!! $errors->first($name) !!}
        </div>
    @endif

@if (!empty($icon))
    @if (!$prepent)
    <div class="input-group-append">{!! $icon !!}</div>
    @endif
</div>
@endif
</div>
