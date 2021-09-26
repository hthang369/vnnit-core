@php
    $options = $attributes->class($class)->getAttributes();
@endphp
<div class="{{$groupClass}}">

    {!! Form::select($name, $items, $selected, $options) !!}

    @if(!empty($help))
        <small id="help-{{ $name }}" class="form-text text-muted">{!! $help !!}</small>
    @endif

    @if(isset($errors) && $errors->has($name))
        <div class="{{ $errors->has($name) ? 'invalid' : '' }}-feedback d-block">
        {!! $errors->first($name) !!}
        </div>
    @endif

</div>
