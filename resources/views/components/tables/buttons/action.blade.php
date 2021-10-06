@props(['data', 'prefix' => config('vnnit-core.prefix')])
<div>
    @foreach ($data['action'] as $item)
        @continue(! with($data, $item->visible))
        @if (!is_callable($item->renderCustom))
            <x-dynamic-component :component="$item->renderCustom" :item="$item" :data="$data"/>
        @else
            {!! with($cellData, $field->renderCustom); !!}
        @endif
    @endforeach
</div>
