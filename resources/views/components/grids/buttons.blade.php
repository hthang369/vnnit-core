@if (with($data ?? '', $visible))
    <a name="{{$key}}" class="{{$class}}"
        href="{{ with($data ?? '', $url) }}"
        title="{{$title}}"
        @foreach ($dataAttributes as $attr => $val)
            data-{{$attr}}="{{$val}}"
        @endforeach
        >
        @if (!blank($icon))
            <i class="{{ array_css_class(['fa', $icon, 'mr-1' => !empty($label)]) }}"></i>
        @endif
        {{$label}}
    </a>
@endif
