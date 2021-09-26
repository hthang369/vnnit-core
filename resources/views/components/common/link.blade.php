<a {!! $attributes->merge($attrs) !!}>
  @if($trim)
    {!! \mb_strimwidth($text, 0, $trim + 3, '...') !!}
  @elseif (!blank($text))
    {!! $text !!}
  @else
    {{ $slot }}
  @endif
</a>
