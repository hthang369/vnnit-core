@props(['tag' => 'h4', 'text', 'darkMode' => false])
<{{$tag}} {!! $attributes->class(['card-header', 'border-dark' => $darkMode]) !!}>
    {{ $text }}
    {!! $slot !!}
</{{$tag}}>
