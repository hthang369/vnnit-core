@php
    $options = $attributes->class($class)->merge(['id' => $name])->getAttributes();
@endphp
<div class="{{$chkGroupCLass}}">
    {!! Form::checkbox($name, $value, $checked, $options) !!}
    {!! Form::label($name, $label, $labelAttr) !!}
</div>
