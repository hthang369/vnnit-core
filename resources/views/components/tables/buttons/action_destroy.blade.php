@props(['item', 'data'])
<a name="{{$item->key}}" class="{{$item->class}} data-remote"
    href="{{ with($data, $item->url) }}"
    title="{{$item->title}}"
    data-trigger-confirm="1"
    data-confirmation-msg="{{__('common.action_question_delete')}}"
    data-method="DELETE"
    data-pjax-target="#gridData">
    @if (!blank($item->icon))
        <i class="{{ array_css_class($item->icon, ['mr-1' => !empty($item->label)]) }}"></i>
    @endif
    {{$item->label}}
</a>
