<{{$tag}} {{ $attributes->class([$field->class])->merge(array_merge(['scope' => 'col'], $field->tdAttr)) }}>
    @if (blank($field->cell) || $isRowHeader)
        {!! $slot !!}
    @elseif (is_callable($field->cell))
        {!! with($cellData, $field->cell); !!}
    @else
        <x-dynamic-component :component="$field->cell" :data="$cellData"/>
    @endif

    @if ($field->sortable && $isRowHeader)
        <x-table-sort :field="$field->key" :except="$except" />
    @endif
</{{$tag}}>
