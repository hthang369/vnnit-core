@props(['data', 'prefix' => config('vnnit-core.prefix')])
<div>
    @foreach ($data['action'] as $item)
        @continue(!$item['visible'])
        @php($component = "$prefix::tables.buttons.action_{$item['key']}")
        <x-dynamic-component :component="$component" :item="$item" :data-id="$data['id']"/>
    @endforeach
</div>
