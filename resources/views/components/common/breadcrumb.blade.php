<nav aria-label="breadcrumb">
  <ol {!! $attributes->merge($attrs) !!}>
    @if(!empty($pages))
      @foreach ($pages as $page => $link )
        <li class="breadcrumb-item">
          @if (blank($link))
            {!! $page !!}
          @else
            <x-link :href="$link" :text="$page" />
          @endif

        </li>
      @endforeach
    @endif
  </ol>
</nav>
