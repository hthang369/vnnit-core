@php
    $options = $attributes->class($class)->getAttributes();
@endphp
<div class="{{$groupClass}}">

    {!! Form::select($name, $items, $selected, $options) !!}

    @include('components.forms.help-block')

    @include('components.forms.errors')

</div>
