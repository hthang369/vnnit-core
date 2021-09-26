<{{$tag}} {!! $attributes->merge($attrs) !!}>
  @if(isset($image['src']))
    <x-image {{ attributes_get($image) }}"/>
  @elseif(isset($icon))
    @icon($icon . $mediaObjectClass ?? '')
  @endif

  {!! $object ?? '' !!}

  <div {!! $body['attrs'] !!}>
    {!! $text ?? '' !!}
    {!! $slot ?? '' !!}
  </div>
</{{$tag}}>
