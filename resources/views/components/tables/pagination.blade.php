@if ($paginator->hasPages())
<div class="d-flex justify-content-between">
    <nav>
        <ul {!! $attributes->merge($attrs) !!}>
            {{-- Previous Page Link --}}
            @if ($links['first_link'])
            <li class="page-item {{$paginator->onFirstPage() ? 'disabled' : ''}}" aria-label="@lang('pagination.previous')">
                @if ($paginator->onFirstPage())
                <span class="page-link" aria-hidden="true">{!! data_get($links, 'first_link.label') !!}</span>
                @else
                <a class="page-link" href="{{ data_get($links, 'first_link.url') }}" rel="prev" aria-label="@lang('pagination.previous')">{!! data_get($links, 'first_link.label') !!}</a>
                @endif
            </li>
            @endif

            {{-- Pagination Elements --}}
          @foreach ($links['elements'] as $link)
            @php
                $disableClass = $link['active'] ? 'active' : '';
                $attrActive = $link['active'] ? 'aria-current="page"' : '';
            @endphp
            <li class="page-item {{$disableClass}}" {!! $attrActive !!} aria-label="{{ $link['label'] }}">
                @if (blank(data_get($link, 'url')))
                    <span class="page-link" aria-hidden="true">{{$link['label']}}</span>
                @else
                    <a class="page-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                @endif
            </li>
          @endforeach

            {{-- Next Page Link --}}
            @if ($links['last_link'])
            <li class="page-item {{$paginator->hasMorePages() ? '' : 'disabled'}}" aria-label="@lang('pagination.next')">
                @if (!$paginator->hasMorePages())
                <span class="page-link" aria-hidden="true">{!! data_get($links, 'last_link.label') !!}</span>
                @else
                <a class="page-link" href="{{ data_get($links, 'last_link.url') }}" rel="next" aria-label="@lang('pagination.next')">{!! data_get($links, 'last_link.label') !!}</a>
                @endif
            </li>
            @endif
        </ul>
    </nav>
    <span class="d-flex align-items-center">
        {!! sprintf(
                translate('table.show_result'),
                $paginator->firstItem(),
                $paginator->lastItem(),
                $paginator->total()
            ) !!}
    </span>
</div>
@endif

