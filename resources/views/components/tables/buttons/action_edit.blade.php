@props(['item', 'data'])
<a name="{{$item->key}}" class="{{$item->class}}"
    href="{{ with($data, $item->url) }}"
    title="{{$item->title}}">
    @if (!blank($item->icon))
        <i class="{{ array_css_class($item->icon, ['mr-1' => !empty($item->label)]) }}"></i>
    @endif
    {{$item->label}}
</a>
