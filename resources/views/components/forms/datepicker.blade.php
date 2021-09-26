@php
    $options = $attributes->class($class)->merge(['autocomplete' => 'off', 'readonly'])->getAttributes();
@endphp
<div id="datepicker" class="input-group date">
    {!! Form::text($name, $value, $options) !!}
</div>
@once
@push('styles')
    <link rel="stylesheet" href="{{asset('css/datepicker.min.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('js/datepicker.min.js')}}" type="text/javascript"></script>
@endpush
@endonce
@push('scripts')
<script>
    $("[name='{{$name}}']").datepicker({
        uiLibrary: 'bootstrap4',
        format: '{{$dateFormat}}'
    });
</script>
@endpush
