@props(['tag' => 'h4', 'text'])
<{{$tag}} {!! $attributes->class(['card-header']) !!}>
    {{ $text }}
    {!! $slot !!}
</{{$tag}}>
