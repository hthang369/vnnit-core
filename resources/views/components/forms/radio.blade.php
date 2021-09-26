@php
    $options = $attributes->class($class)->merge(['id' => $name])->getAttributes();
@endphp
<div class="{{$chkGroupCLass}}">
    {!! Form::radio($name, $value, $checked, $options) !!}
    {!! Form::label($name, $label, $labelAttr) !!}
</div>
