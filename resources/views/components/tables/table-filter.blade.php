<td {{$attributes->merge(['scope' => 'col'])}}>
    @if (!blank($field->cell) && is_callable($field->cell))
        {!! with($cellData, $field->cell); !!}
    @elseif (str_is($field->key, 'action'))
        {!! Form::button('<i class="fas fa-filter"></i>', ['class' => 'btn btn-sm btn-outline-primary ml-2', 'onclick' => 'filterAction()']) !!}
    @else
        @if (str_is($field->dataType, 'date'))
            <x-dynamic-component :component="$type" :name="$field->key" :value="reuqest($field->key)" />
        @elseif (str_is($type, 'select'))
            {!! Form::select($field->key, $field->lookup->items, reuqest($field->key), ['class' => 'form-control form-control-sm', 'placeholder' => translate('table.select')]) !!}
        @else
            {!! Form::text($field->key, request($field->key), ['class' => 'form-control form-control-sm']) !!}
        @endif
    @endif
</td>
@once
@push('scripts')
<script>
    function filterAction() {
        $('.table_filter').find('input').each(function(idx, item) {
            let params = new URLSearchParams(location.search)
            if (item.value) {
                params.set(item.name, item.value)
            } else {
                params.delete(item.name)
            }
            let url = params.toString() == '' ? '' : '?' + params.toString();
            window.location.assign('{{request()->url()}}' + url)
        });
    }
</script>
@endpush
@endonce
