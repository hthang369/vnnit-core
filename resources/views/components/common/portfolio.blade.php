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
                {{data_get($data, 'excerpt')}}
            </p>
        </x-card>
    </div>
    @endforeach
</x-card-group>
