<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Needs Your Attention
        </x-slot>
        <x-slot name="description">
            Click a card to go straight to the list — cleared items turn gray.
        </x-slot>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($this->getCards() as $card)
                @php $needsAction = $card['count'] > 0; @endphp
                <a href="{{ $card['url'] }}"
                   class="group flex items-center gap-3 rounded-xl border p-4 transition
                          {{ $needsAction
                                ? 'border-danger-200 bg-danger-50 hover:border-danger-300 hover:shadow-sm dark:border-danger-500/30 dark:bg-danger-500/10'
                                : 'border-gray-200 bg-gray-50 hover:border-gray-300 dark:border-white/10 dark:bg-white/5' }}">
                    <span @class([
                        'flex h-11 w-11 flex-none items-center justify-center rounded-lg',
                        'bg-danger-100 text-danger-600 dark:bg-danger-500/20 dark:text-danger-400' => $needsAction,
                        'bg-gray-200 text-gray-400 dark:bg-white/10 dark:text-gray-500' => ! $needsAction,
                    ])>
                        <x-filament::icon :icon="$needsAction ? $card['icon'] : 'heroicon-o-check-circle'" class="h-6 w-6" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-2xl font-bold {{ $needsAction ? 'text-danger-700 dark:text-danger-400' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $card['count'] }}
                        </span>
                        <span class="block text-xs font-medium leading-tight text-gray-500 dark:text-gray-400">
                            {{ $card['label'] }}
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
