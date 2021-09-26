<div class="{{$responsive}}">
    <table {{ $attributes->class($tableClass) }}>
        <thead>
            <x-table-row scope="header">
                @foreach ($fields as $field)
                    @continue(!$field->visible)
                    <x-table-column :field="$field" :isHeader="true">
                        {!! $field->label !!}
                    </x-table-column>
                @endforeach
            </x-table-row>
            @if ($isFilters)
            <x-table-row scope="filter" class="table_filter">
                @foreach ($fields as $field)
                    @continue(!$field->visible)
                    @if ($field->filtering || str_is($field->dataType, 'buttons'))
                        <x-table-filter class="p-1" :field="$field" />
                    @else
                        <x-table-column :field="new Vnnit\Core\Helpers\DataColumn" />
                    @endif
                @endforeach
            </x-table-row>
            @endif
        </thead>
        <tbody>
            @forelse ($items as $item)
                <x-table-row>
                    @foreach ($fields as $field)
                        @continue(!$field->visible)
                        <x-table-column :field="$field" :data="$item">
                            @if (!is_null($field->lookup->dataSource))
                                {!! data_get($field->lookup->items, data_get($item, $field->key)) !!}
                            @else
                                {!! data_get($item, $field->key) !!}
                            @endif
                        </x-table-column>
                    @endforeach
                </x-table-row>
            @empty
                @php
                    $field = new Vnnit\Core\Helpers\DataColumn;
                    $field->tdAttr = ['colspan' => count($fields)];
                    $prefix = config('vnnit-core.prefix');
                @endphp
                <x-table-row>
                    <x-table-column :field="$field">
                        <x-alert type="warning">
                            @lang("$prefix::table.no_item_found")
                        </x-alert>
                    </x-table-column>
                </x-table-row>
            @endforelse
        </tbody>
    </table>

    @section('paginator-info')
        @if (is_array($pagination))
            <x-pagination
                :items="$items"
                :total="$pagination['total']"
                :current="$pagination['currentPage']"
                :pages="$pagination['pages']"
                :except="$pagination['except']" />
        @else
            {!! $pagination->links() !!}
        @endif
    @show

</div>
