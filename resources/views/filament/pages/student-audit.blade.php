<x-filament-panels::page>

    @if (! $student)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-6 text-center text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
            No student selected. Open this page from the "Audit" button on a student's row in the Students list.
        </div>
    @else
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-gray-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">{{ $student['name'] }}</h2>
                    <p class="text-sm text-gray-500">
                        S/D of {{ $student['father'] ?: '—' }}
                        &middot; Roll {{ $student['roll'] ?: '—' }}
                        @if($student['reg']) &middot; Reg. {{ $student['reg'] }} @endif
                    </p>
                    <p class="mt-1 text-xs text-gray-400">{{ $student['program'] ?: '—' }} &middot; {{ $student['department'] ?: '—' }}</p>
                </div>
                @if ($this->getPdfUrl())
                    <a href="{{ $this->getPdfUrl() }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export PDF
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 dark:border-white/10 dark:bg-white/5">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Event</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Details</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">By</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="px-4 py-2.5 whitespace-nowrap text-gray-500">{{ $log['date'] }}</td>
                            <td class="px-4 py-2.5 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $log['event'] }}</td>
                            <td class="px-4 py-2.5 text-gray-700 dark:text-gray-200">{{ $log['message'] ?: '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-500">{{ $log['actor'] ?: 'System' }}</td>
                            <td class="px-4 py-2.5">
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium bg-{{ $log['level_color'] }}-100 text-{{ $log['level_color'] }}-700">
                                    {{ $log['level'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-400">No recorded activity for this student yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

</x-filament-panels::page>
