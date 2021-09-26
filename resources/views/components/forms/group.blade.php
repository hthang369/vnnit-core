<div {{ $attributes->class(['form-group', 'form-row' => $inline]) }}>
    @section('label')
        @if (!empty($label))
        {!! Form::label($labelFor, $label, ['class' => ['col-form-label', $labelClass]]) !!}
        @endif
    @show

    {!! $slot !!}
</div>
