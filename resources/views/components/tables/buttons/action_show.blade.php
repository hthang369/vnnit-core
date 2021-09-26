@props(['item', 'dataId'])
<a name="{{$item['key']}}" class="btn btn-sm {{$item['class']}}"
    href="{{ route("{$sectionCode}.{$item['key']}", $dataId)}}"
    title="{{$item['title']}}">
    {{$item['label']}}
    @if (!empty($item['icon']))
        <i class="{{$item['icon']}}"></i>
    @endif
</a>
