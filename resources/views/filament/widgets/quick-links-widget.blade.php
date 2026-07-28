<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Links
        </x-slot>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($this->getLinks() as $link)
                <a href="{{ $link['url'] }}"
                   class="group flex flex-col items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 p-4 text-center transition hover:border-primary-300 hover:bg-primary-50 dark:border-white/10 dark:bg-white/5 dark:hover:bg-primary-500/10">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 text-primary-600 transition group-hover:bg-primary-600 group-hover:text-white dark:bg-primary-500/20 dark:text-primary-400">
                        <x-filament::icon :icon="$link['icon']" class="h-5 w-5" />
                    </span>
                    <span class="text-xs font-medium leading-tight text-gray-600 dark:text-gray-300">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
