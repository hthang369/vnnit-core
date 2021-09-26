@if(!empty($href))
  <a {!! $attributes->merge($attrs) !!}>
    {{ $text ?? '' }}
    {{ $slot ?? '' }}
  </a>
@else
  <span {!! $attributes->merge($attrs) !!}>
    {{ $text }}
    {{ $slot ?? '' }}
  </span>
@endif
