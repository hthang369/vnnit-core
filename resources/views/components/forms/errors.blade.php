@if(isset($errors) && $errors->has($name))
    <div class="{{ $errors->has($name) ? 'invalid' : '' }}-feedback d-block">
    {!! $errors->first($name) !!}
    </div>
@endif
