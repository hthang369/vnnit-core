<x-card-group :size="$size" :size-cols="$cols">
    @foreach ($items as $data)
    <div {!! $attributes->merge($attrs) !!}>
        <x-card>
            <x-slot name="imgSrc">
                <span class="img-wrapper">
                    <x-link :href="data_get($data, 'link')">
                        <x-image
                            :src="data_get($data, 'images.src')"
                            :alt="data_get($data, 'images.name', pathinfo(data_get($data, 'images.src'), PATHINFO_FILENAME))"
                            :class="data_get($data, 'images.class')" />
                    </x-link>
                </span>
            </x-slot>

            <x-slot name="title">
                <h5 class="card-title">
                    <x-link :href="data_get($data, 'link')">
                        {{data_get($data, 'title')}}
                    </x-link>
                </h5>
            </x-slot>

            <p class="card-text">
                <span class="text-danger">{{data_get($data, 'promotion_price')}}</span>
                <span class="text-decoration-line">{{data_get($data, 'price')}}</span>
                <x-badge variant="danger">{{data_get($data, 'promotion_percent')}}</x-badge>
            </p>
        </x-card>
    </div>
    @endforeach
</x-card-group>
