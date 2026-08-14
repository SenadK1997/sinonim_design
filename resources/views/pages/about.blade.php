<x-layouts.app :title="__('About')">
    @php
        $brand = \App\Models\Setting::get('brand_name', 'SinonimDesign');
        $about = \App\Models\Setting::localized('about_text');
        $ig = \App\Models\Setting::get('instagram_handle', 'sinonim_design');

        // Placeholder photos she'll swap out — pulled from the media library so
        // whatever she uploads (mom, sewing machine, workshop) shows up here.
        $products = \App\Models\Product::published()->with('media')->get();
        $storyPhotos = $products->map(fn($p) => $p->primaryImageUrl('large'))->filter()->values();
    @endphp

    {{-- Intro — big editorial cover --}}
    <section class="container-wide pt-8 md:pt-10 pb-4">
        <div class="relative overflow-hidden rounded-[2rem] md:rounded-[2.5rem] min-h-[60vh] flex items-center bg-gradient-to-br from-[var(--color-brand-100)] via-[var(--color-brand-200)] to-[var(--color-brand-300)]">
            <div class="hidden md:block absolute -bottom-32 -right-20 font-display italic text-[26rem] leading-none opacity-[0.06] pointer-events-none select-none">
                {{ substr($brand, 0, 2) }}
            </div>

            <div class="relative px-8 md:px-16 lg:px-20 py-20 md:py-24 w-full">
                <p class="eyebrow mb-6">{{ __('The story') }}</p>
                <h1 class="font-display font-light text-5xl md:text-7xl lg:text-8xl leading-[0.95] tracking-tight max-w-3xl">
                    <em class="italic">{{ __('Your synonym') }}</em><br>
                    {{ __('is your strength.') }}
                </h1>
                <p class="mt-8 max-w-lg text-base md:text-lg opacity-80 leading-relaxed">
                    {{ __('Handmade in Sarajevo, in limited editions — so every piece stays recognizable. Chosen fabrics, careful stitches, and a story behind every collection.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Chapter 1 — main story text --}}
    <section class="container-wide py-24 md:py-32">
        <div class="max-w-3xl mx-auto">
            <p class="eyebrow mb-6">{{ __('Chapter one') }}</p>
            <h2 class="font-display font-light text-4xl md:text-5xl leading-[1.05] mb-10">{{ __('Where it started.') }}</h2>

            <div class="prose max-w-none text-lg leading-relaxed opacity-90 space-y-6 font-serif">
                @if($about)
                    {!! nl2br(e($about)) !!}
                @else
                    <p>{{ $brand }} {{ __('is a handmade clothing collection born from love for detail and quality materials. Each piece is unique — from the fabric selection to the final stitch.') }}</p>
                    <p>{{ __('We work in limited editions. Not many of the same piece, so what you\'re buying stays recognizable — a small love letter to slow craft.') }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Photo strip — 3 images (product photos as placeholders; she\'ll upload workshop/mom photos later) --}}
    @if($storyPhotos->count() >= 3)
        <section class="container-wide pb-24 md:pb-32">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                @foreach($storyPhotos->take(3) as $photo)
                    <div class="relative aspect-[3/4] overflow-hidden rounded-2xl md:rounded-3xl bg-[var(--color-brand-100)]">
                        <img src="{{ $photo }}" alt="" class="w-full h-full object-cover" loading="lazy">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Chapter 2 — the process (space reserved for photos she\'ll add) --}}
    <section class="bg-[var(--color-brand-50)] py-24 md:py-32">
        <div class="container-wide grid md:grid-cols-2 gap-12 md:gap-20 items-center">
            <div>
                <p class="eyebrow mb-6">{{ __('Chapter two') }}</p>
                <h2 class="font-display font-light text-4xl md:text-5xl lg:text-6xl leading-[1.05] mb-8">
                    {{ __('Made by hand.') }}<br>
                    <em class="italic opacity-80">{{ __('One at a time.') }}</em>
                </h2>
                <div class="space-y-4 max-w-md text-base opacity-80 leading-relaxed">
                    <p>{{ __('Every piece passes through the workshop before it reaches you — cut, sewn, and inspected. No factory line, no rush.') }}</p>
                    <p>{{ __('That\'s why we make limited editions: so each piece stays personal, and each collection tells its own small story.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 md:gap-4">
                @foreach($storyPhotos->slice(3, 4) as $photo)
                    <div class="relative aspect-square overflow-hidden rounded-2xl bg-[var(--color-brand-200)]">
                        <img src="{{ $photo }}" alt="" class="w-full h-full object-cover" loading="lazy">
                    </div>
                @endforeach
                @if($storyPhotos->slice(3, 4)->count() < 4)
                    {{-- Placeholder slots ready for machine + workshop photos --}}
                    @for($i = $storyPhotos->slice(3, 4)->count(); $i < 4; $i++)
                        <div class="relative aspect-square overflow-hidden rounded-2xl bg-gradient-to-br from-[var(--color-brand-100)] to-[var(--color-brand-200)] flex items-center justify-center">
                            <span class="font-display italic text-4xl opacity-30">✦</span>
                        </div>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    {{-- Chapter 3 — values re-stated --}}
    <section class="container-wide py-24 md:py-32">
        <div class="max-w-3xl mx-auto text-center mb-16">
            <p class="eyebrow mb-6">{{ __('Chapter three') }}</p>
            <h2 class="font-display font-light text-4xl md:text-5xl lg:text-6xl leading-[1.05]">
                {{ __('What we care about.') }}
            </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8 md:gap-12 max-w-5xl mx-auto">
            @foreach([
                [__('Handmade'), __('Every piece leaves our workshop carefully sewn and inspected before delivery.')],
                [__('Selected materials'), __('Fabrics chosen for feel, drape, and how they age — nothing shortcut.')],
                [__('Natural & premium'), __('Natural fibers first — gentle on skin, comfortable to wear, made to last.')],
            ] as [$title, $body])
                <div class="text-center">
                    <div class="font-display italic text-5xl md:text-6xl opacity-30 mb-4">0{{ $loop->iteration }}</div>
                    <h3 class="font-display text-2xl mb-3">{{ $title }}</h3>
                    <p class="text-sm opacity-70 leading-relaxed max-w-xs mx-auto">{{ $body }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Closing CTA --}}
    <section class="bg-[var(--color-ink)] text-[var(--color-brand-100)] py-20 md:py-24">
        <div class="container-wide text-center">
            <p class="eyebrow text-[var(--color-brand-300)] mb-4">{{ $brand }}</p>
            <h2 class="font-display font-light italic text-3xl md:text-5xl leading-[1.1] max-w-3xl mx-auto">
                {{ __('Come see the pieces made just for the woman you already are.') }}
            </h2>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-[var(--color-brand-100)] text-[var(--color-ink)] text-xs tracking-[0.25em] uppercase hover:bg-white transition">
                    {{ __('Shop the collection') }} →
                </a>
                @if($ig)
                    <a href="https://instagram.com/{{ ltrim($ig, '@') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-4 text-xs tracking-[0.25em] uppercase link-underline text-[var(--color-brand-100)]">
                        {{ __('Follow on Instagram') }}
                    </a>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
