<x-filament-panels::page>
    @if ($intro ?? null)
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $intro }}</p>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/10">
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Section</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Type</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-2.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                @foreach ($this->getSections() as $row)
                    <tr class="{{ (($row['kind'] === 'page' && ! $row['status']) || $row['kind'] === 'pending') ? 'opacity-50' : '' }}">
                        {{-- Section name --}}
                        <td class="px-4 py-3 align-middle">
                            @if ($row['kind'] === 'page')
                                <div x-data="{ editing: false, value: @js($row['name']) }" class="flex items-center gap-2">
                                    <template x-if="!editing">
                                        <button type="button" @click="editing = true" class="font-semibold text-gray-900 dark:text-white rounded px-1.5 py-0.5 -mx-1.5 hover:bg-gray-100 dark:hover:bg-white/5" x-text="value"></button>
                                    </template>
                                    <template x-if="editing">
                                        <input type="text" x-model="value" x-ref="input"
                                            @keydown.enter="$refs.input.blur()"
                                            @blur="editing = false; $wire.renameSection(@js($row['slug']), value)"
                                            x-init="$nextTick(() => { if (editing) $refs.input.focus() })"
                                            class="rounded-md border-gray-300 text-sm font-semibold dark:border-white/20 dark:bg-white/5 dark:text-white focus:border-primary-500 focus:ring-primary-500" />
                                    </template>
                                    <span title="Click name to rename" class="text-gray-400 dark:text-gray-500 text-xs">✎</span>
                                </div>
                            @else
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $row['name'] }}</span>
                            @endif

                            @if ($row['crossLink'] ?? null)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $row['crossLink'] }}</p>
                            @endif
                            @if ($row['detail'] ?? null)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $row['detail'] }}</p>
                            @endif
                            @if ($row['note'] ?? null)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $row['note'] }}</p>
                            @endif
                        </td>

                        {{-- Type --}}
                        <td class="px-4 py-3 align-middle">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide
                                {{ match($row['kind']) {
                                    'page' => 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-300',
                                    'collection' => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-300',
                                    'setting' => 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-300',
                                    default => 'bg-gray-100 text-gray-400 dark:bg-white/5 dark:text-gray-500',
                                } }}">
                                {{ match($row['kind']) { 'page' => 'Page', 'collection' => 'Collection', 'setting' => 'Setting', default => 'Not live yet' } }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 align-middle">
                            @if ($row['kind'] === 'page')
                                <button type="button" wire:click="toggleSection('{{ $row['slug'] }}')" class="inline-flex items-center gap-2 text-xs font-semibold {{ $row['status'] ? 'text-success-600 dark:text-success-400' : 'text-gray-400 dark:text-gray-500' }}">
                                    <span class="relative inline-flex h-[18px] w-[34px] items-center rounded-full transition {{ $row['status'] ? 'bg-success-500/20' : 'bg-gray-200 dark:bg-white/10' }}">
                                        <span class="absolute h-[14px] w-[14px] rounded-full transition {{ $row['status'] ? 'right-0.5 bg-success-500' : 'left-0.5 bg-gray-400 dark:bg-gray-500' }}"></span>
                                    </span>
                                    {{ $row['status'] ? 'Live' : 'Hidden' }}
                                </button>
                            @elseif ($row['kind'] === 'collection')
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $row['count'] }}</span> active
                                    @if ($row['manageUrl'])
                                        &middot; <a href="{{ $row['manageUrl'] }}" class="font-semibold text-primary-600 hover:underline dark:text-primary-400">Manage &rarr;</a>
                                    @endif
                                </span>
                            @elseif ($row['kind'] === 'setting')
                                <span class="text-xs text-gray-400 dark:text-gray-500">&mdash;</span>
                            @else
                                <span class="text-xs font-bold uppercase text-danger-500">Pending decision</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3 align-middle text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if ($row['kind'] === 'page' && ($row['previewUrl'] ?? null))
                                    <a href="{{ $row['previewUrl'] }}" target="_blank" title="Preview" class="rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-white/5 dark:hover:text-white">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                @endif
                                @if (in_array($row['kind'], ['page', 'setting'], true) && ($row['editUrl'] ?? null))
                                    <a href="{{ $row['editUrl'] }}" title="Edit" class="rounded p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-white/5 dark:hover:text-white">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
