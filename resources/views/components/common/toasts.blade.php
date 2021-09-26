<div {!! $attributes->merge($attrs) !!}>
    <div class="toast-header {{$headerClass}}">
    {{-- <img src="..." class="rounded mr-2" alt="..."> --}}
    <span class="mr-2">@icon($type)</span>
    <strong class="mr-auto">{{$title}}</strong>
    @if ($dismissible)
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
    </div>
    <div class="toast-body">
    {!! $slot !!}
    </div>
</div>
