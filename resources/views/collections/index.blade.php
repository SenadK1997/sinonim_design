<x-layouts.app :title="__('Collections')">
    <section class="container-wide py-12 md:py-16">
        <p class="eyebrow mb-2">{{ __('Collections') }}</p>
        <h1 class="font-display text-4xl md:text-5xl mb-12">{{ __('Collections') }}</h1>

        @if($collections->isEmpty())
            <p class="text-center py-24 opacity-60">{{ __('No published collections yet.') }}</p>
        @else
            <div class="grid gap-6 md:gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach($collections as $col)
                    <a href="{{ route('collections.show', $col->slug) }}" class="product-card group block relative overflow-hidden aspect-[4/5] bg-[var(--color-brand-100)] rounded-2xl md:rounded-3xl">
                        @if($col->coverUrl('large'))
                            <img src="{{ $col->coverUrl('large') }}" alt="{{ $col->name }}" class="product-card-image w-full h-full object-cover" loading="lazy">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <p class="eyebrow text-white/80 mb-1">{{ __('Collection') }}</p>
                            <h2 class="font-display text-2xl md:text-3xl">{{ $col->name }}</h2>
                            @if($col->description)
                                <p class="mt-2 text-sm opacity-90 line-clamp-2">{{ $col->description }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
