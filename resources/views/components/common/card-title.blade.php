@props(['tag' => 'h6', 'text'])
<{{$tag}} {!! $attributes->class(['card-title']) !!}>
    {{ $text }}
    {!! $slot !!}
</{{$tag}}>
