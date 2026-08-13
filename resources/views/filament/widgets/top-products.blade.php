<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-baseline justify-between gap-3">
                <span>Najprodavaniji proizvodi</span>
                <span class="text-xs font-normal opacity-60">Zadnjih 30 dana</span>
            </div>
        </x-slot>

        @php $rows = $this->getTopProducts(); @endphp

        @if(empty($rows))
            <div class="py-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 dark:bg-white/5 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 opacity-50">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941" />
                    </svg>
                </div>
                <p class="text-sm font-medium">Još nema podataka o prodaji</p>
                <p class="text-xs opacity-60 mt-1">Kada prve narudžbe stignu, ovdje će se prikazati top 10 proizvoda.</p>
            </div>
        @else
            <ul class="divide-y divide-gray-100 dark:divide-white/5 -mx-2">
                @foreach($rows as $i => $r)
                    @php
                        $rank = $i + 1;
                        $rankBadge = match($rank) {
                            1 => ['bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300', '🥇'],
                            2 => ['bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-300', '🥈'],
                            3 => ['bg-orange-100 text-orange-800 dark:bg-orange-500/20 dark:text-orange-300', '🥉'],
                            default => ['bg-transparent text-gray-400 dark:text-gray-500', null],
                        };
                    @endphp

                    <li class="flex items-center gap-4 px-2 py-3.5 hover:bg-gray-50 dark:hover:bg-white/5 rounded-md transition">
                        {{-- Rank --}}
                        <div class="w-9 flex items-center justify-center shrink-0">
                            @if($rankBadge[1])
                                <span class="text-xl leading-none">{{ $rankBadge[1] }}</span>
                            @else
                                <span class="font-mono text-sm tabular-nums {{ $rankBadge[0] }} px-2 py-0.5 rounded-md">{{ str_pad((string) $rank, 2, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </div>

                        {{-- Thumbnail --}}
                        <div class="w-12 h-14 shrink-0 overflow-hidden bg-gray-100 dark:bg-white/5 rounded-sm">
                            @if($r['thumb'])
                                <img src="{{ $r['thumb'] }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Name + bar --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm truncate">{{ $r['name'] }}</p>
                            <div class="mt-2 flex items-center gap-3">
                                <div class="flex-1 h-1 rounded-full bg-gray-100 dark:bg-white/10 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-600 rounded-full transition-all duration-500" style="width: {{ $r['bar_percent'] }}%"></div>
                                </div>
                                <span class="text-xs opacity-60 tabular-nums whitespace-nowrap">{{ $r['qty'] }} {{ $r['qty'] === 1 ? 'kom' : 'kom' }}</span>
                            </div>
                        </div>

                        {{-- Revenue --}}
                        <div class="text-right shrink-0 pl-4">
                            <p class="font-semibold text-base tabular-nums whitespace-nowrap">{{ $r['revenue'] }}</p>
                            <p class="text-[10px] uppercase tracking-widest opacity-50 mt-0.5">Prihod</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
