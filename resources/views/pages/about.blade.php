<x-layouts.app :title="__('About')">
    <section class="container-wide py-16 md:py-24 max-w-3xl">
        <p class="eyebrow mb-3">{{ __('About the brand') }}</p>
        <h1 class="font-display text-4xl md:text-5xl mb-8">{{ \App\Models\Setting::get('brand_name', 'SinonimDesign') }}</h1>

        @php $about = \App\Models\Setting::localized('about_text'); @endphp

        @if($about)
            <div class="prose max-w-none text-lg leading-relaxed opacity-90">
                {!! nl2br(e($about)) !!}
            </div>
        @else
            <div class="prose max-w-none text-lg leading-relaxed opacity-90 space-y-6">
                <p>SinonimDesign je ručno rađena kolekcija odjeće — svaki komad sašiven je pažljivo, u malim serijama, s idejom da se izdvoji od masovne proizvodnje.</p>
                <p>Materijali koje biramo su prirodni i udobni, a kroj je pravljen da traje. Uređujte ovaj tekst u administraciji sajta.</p>
            </div>
        @endif

        <div class="mt-12 grid md:grid-cols-3 gap-8 text-sm">
            <div>
                <p class="font-display text-2xl mb-2">{{ __('Handmade') }}</p>
                <p class="opacity-70">{{ __('Every piece leaves our workshop carefully sewn and inspected before delivery.') }}</p>
            </div>
            <div>
                <p class="font-display text-2xl mb-2">{{ __('Small batches') }}</p>
                <p class="opacity-70">{{ __('We don\'t make large quantities of the same piece — you\'re buying something not everyone will be wearing.') }}</p>
            </div>
            <div>
                <p class="font-display text-2xl mb-2">{{ __('Natural fabrics') }}</p>
                <p class="opacity-70">{{ __('We choose fabrics that are comfortable, durable, and gentle on the skin.') }}</p>
            </div>
        </div>
    </section>
</x-layouts.app>
