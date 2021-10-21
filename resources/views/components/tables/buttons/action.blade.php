@props(['data', 'prefix' => config('vnnit-core.prefix')])
<div>
    @foreach ($data['action'] as $item)
        @continue(! with($data, $item->visible))
        @if (blank($item->renderCustom))
            {!! $item->setData($data)->render() !!}
        @elseif (is_callable($item->renderCustom))
            {!! with($data, $field->renderCustom) !!}
        @else
            <x-dynamic-component :component="$item->renderCustom" :item="$item" :data="$data"/>
        @endif
    @endforeach
</div>
