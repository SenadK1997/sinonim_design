<x-layouts.app :title="$collection->name">
    {{-- Cover --}}
    @if($collection->coverUrl('large'))
        <section class="relative aspect-[16/9] md:aspect-[21/9] overflow-hidden">
            <img src="{{ $collection->coverUrl('large') }}" alt="{{ $collection->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-6">
                <p class="eyebrow text-white/80 mb-3">{{ __('Collection') }}</p>
                <h1 class="font-display text-4xl md:text-6xl lg:text-7xl">{{ $collection->name }}</h1>
                @if($collection->description)
                    <p class="mt-4 max-w-xl text-sm md:text-base opacity-90">{{ $collection->description }}</p>
                @endif
            </div>
        </section>
    @else
        <section class="container-wide pt-16 pb-8 text-center">
            <p class="eyebrow mb-3">{{ __('Collection') }}</p>
            <h1 class="font-display text-4xl md:text-6xl">{{ $collection->name }}</h1>
            @if($collection->description)
                <p class="mt-4 max-w-xl mx-auto opacity-80">{{ $collection->description }}</p>
            @endif
        </section>
    @endif

    <section class="container-wide py-16">
        @if($products->isEmpty())
            <p class="text-center py-16 opacity-60">{{ __('This collection has no published products yet.') }}</p>
        @else
            <div class="grid gap-6 md:gap-8 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
