<x-layouts.app :title="__('About')">
    <section class="container-wide py-16 md:py-24 max-w-3xl">
        <p class="eyebrow mb-3">{{ __('About the brand') }}</p>
        <h1 class="font-display text-4xl md:text-5xl mb-8">{{ \App\Models\Setting::get('brand_name', 'SinonimDesign') }}</h1>

        @php $about = \App\Models\Setting::get('about_text'); @endphp

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
                <p class="font-display text-2xl mb-2">Ručno rađeno</p>
                <p class="opacity-70">Svaki komad izlazi iz naše radionice — pažljivo sašiven i pregledan prije isporuke.</p>
            </div>
            <div>
                <p class="font-display text-2xl mb-2">Male serije</p>
                <p class="opacity-70">Ne pravimo veliku količinu istih komada — kupujete nešto što neće nositi svi.</p>
            </div>
            <div>
                <p class="font-display text-2xl mb-2">Prirodni materijali</p>
                <p class="opacity-70">Biramo tkanine koje su ugodne, izdržljive i prijateljski nastrojene prema koži.</p>
            </div>
        </div>
    </section>
</x-layouts.app>
