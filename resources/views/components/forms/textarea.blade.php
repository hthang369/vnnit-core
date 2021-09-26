@php
    $options = $attributes->class(['form-control', $class])->getAttributes();
@endphp
<div class="{{$groupClass}}">
    {!! Form::textarea($name, $value, $options) !!}

    @if(!empty($help))
        <small id="help-{{ $name }}" class="form-text text-muted">{!! $help !!}</small>
    @endif

    @if(isset($errors) && $errors->has($name))
        <div class="{{ $errors->has($name) ? 'invalid' : '' }}-feedback d-block">
        {!! $errors->first($name) !!}
        </div>
    @endif
</div>
