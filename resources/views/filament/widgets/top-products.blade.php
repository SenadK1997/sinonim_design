<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Najprodavaniji proizvodi (zadnjih 30 dana)</x-slot>

        @php $rows = $this->getTopProducts(); @endphp

        @if(empty($rows))
            <div class="py-12 text-center text-sm opacity-60">
                Još nema podataka o prodaji. Kada narudžbe počnu stizati, ovdje će se prikazati top 10.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-4 font-medium">#</th>
                            <th class="py-2 pr-4 font-medium">Proizvod</th>
                            <th class="py-2 pr-4 font-medium text-center">Prodato</th>
                            <th class="py-2 pr-4 font-medium text-right">Prihod</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $i => $r)
                            <tr class="border-b border-gray-100 dark:border-gray-800 last:border-0">
                                <td class="py-2.5 pr-4 opacity-50 tabular-nums">{{ $i + 1 }}</td>
                                <td class="py-2.5 pr-4 font-medium">{{ $r['name'] }}</td>
                                <td class="py-2.5 pr-4 text-center tabular-nums">{{ $r['qty'] }}</td>
                                <td class="py-2.5 pr-4 text-right tabular-nums">{{ $r['revenue'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
