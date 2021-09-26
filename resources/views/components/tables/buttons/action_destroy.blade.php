@props(['item', 'dataId'])
<a name="{{$item['key']}}" class="btn btn-sm {{$item['class']}} data-remote"
    href="{{ route("{$sectionCode}.{$item['key']}", $dataId)}}"
    title="{{$item['title']}}"
    data-trigger-confirm="1"
    data-confirmation-msg="{{__('common.action_question_delete')}}"
    data-method="DELETE"
    data-pjax-target="#gridData">
    {{$item['label']}}
    @if (!empty($item['icon']))
        <i class="{{$item['icon']}}"></i>
    @endif
</a>
