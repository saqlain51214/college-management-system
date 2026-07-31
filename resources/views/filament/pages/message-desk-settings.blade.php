<x-filament-panels::page>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Pick which design shows on the home page's "Leadership Message Desk" section. Manage the leaders themselves (names, photos, messages) under
        <span class="font-semibold text-gray-700 dark:text-gray-200">Website Pages &rarr; About Us &rarr; Leadership Messages &rarr; Manage</span>.
    </p>

    <form wire:submit.prevent="save" class="mt-6 space-y-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (self::$templates as $key => $t)
                <label class="relative flex cursor-pointer flex-col gap-3 rounded-2xl border-2 p-4 transition
                    {{ $template === $key
                        ? 'border-primary-500 bg-primary-50/60 dark:bg-primary-500/10'
                        : 'border-gray-200 hover:border-gray-300 dark:border-white/10 dark:hover:border-white/20' }}">
                    <input type="radio" wire:model.live="template" value="{{ $key }}" class="sr-only">

                    @if ($template === $key)
                        <span class="absolute right-3 top-3 flex h-5 w-5 items-center justify-center rounded-full bg-primary-500 text-white">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    @endif

                    {{-- Mini visual preview swatch --}}
                    <div class="h-24 overflow-hidden rounded-lg">
                        @switch($key)
                            @case('cards')
                                <div class="flex h-full items-center justify-center gap-1.5 bg-[#F7F4EC] p-2.5">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="h-full flex-1 rounded bg-white shadow-sm">
                                            <div class="h-1/2 rounded-t bg-gradient-to-br from-[#16273F] to-[#0B1420]"></div>
                                        </div>
                                    @endfor
                                </div>
                                @break
                            @case('spotlight')
                                <div class="flex h-full gap-1.5 bg-gradient-to-br from-[#16273F] to-[#0B1420] p-2.5">
                                    <div class="h-full w-1/3 rounded bg-white/10"></div>
                                    <div class="flex h-full flex-1 flex-col justify-center gap-1 rounded bg-white/5 p-1.5">
                                        <div class="h-1 w-3/4 rounded bg-[#C9A227]"></div>
                                        <div class="h-1 w-1/2 rounded bg-white/40"></div>
                                    </div>
                                </div>
                                @break
                            @case('diploma')
                                <div class="flex h-full items-center justify-center bg-[#F7F4EC] p-2">
                                    <div class="flex h-full w-full items-center justify-center rounded border-2 border-[#C9A227]">
                                        <div class="h-10 w-10 rounded-sm bg-gradient-to-br from-[#16273F] to-[#0B1420] ring-2 ring-[#C9A227]"></div>
                                    </div>
                                </div>
                                @break
                            @case('ribbon')
                                <div class="flex h-full flex-col justify-center gap-1.5 bg-[#F7F4EC] p-2.5">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div class="flex h-1/3 items-center gap-1.5 rounded bg-white p-1 shadow-sm">
                                            <div class="h-full w-6 rounded-sm bg-gradient-to-br from-[#16273F] to-[#0B1420]"></div>
                                            <div class="h-1 flex-1 rounded bg-gray-200"></div>
                                        </div>
                                    @endfor
                                </div>
                                @break
                            @case('glass')
                                <div class="flex h-full items-center justify-center gap-2 bg-gradient-to-br from-[#EFE9DC] to-[#EAF0EF] p-2.5">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div class="flex h-full flex-1 items-center justify-center rounded-lg bg-white/60">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#16273F] to-[#0B1420] ring-2 ring-[#C9A227]/50"></div>
                                        </div>
                                    @endfor
                                </div>
                                @break
                            @case('minimal')
                                <div class="flex h-full items-center justify-center gap-4 bg-white p-2.5">
                                    @for ($i = 0; $i < 2; $i++)
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#16273F] to-[#0B1420]"></div>
                                            <div class="h-0.5 w-4 bg-[#C9A227]"></div>
                                        </div>
                                    @endfor
                                </div>
                                @break
                        @endswitch
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $t['label'] }}</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-gray-500 dark:text-gray-400">{{ $t['description'] }}</p>
                    </div>
                </label>
            @endforeach
        </div>

        <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Template
        </button>
    </form>
</x-filament-panels::page>
