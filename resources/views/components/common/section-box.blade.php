<section {{ $attributes }}>
    <x-section-title :title="$title" />
    <div class="{{ $wrapper }}">
        <div class="section-body">{{ $slot }}</div>
    </div>
</section>
