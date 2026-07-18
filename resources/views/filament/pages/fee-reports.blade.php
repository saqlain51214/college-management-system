<x-filament-panels::page>
    @php
        $rs = fn ($n) => 'Rs. ' . number_format((float) $n);
    @endphp

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Total Billed', $rs($summary['billed'] ?? 0), '#6b7280'],
            ['Total Collected', $rs($summary['collected'] ?? 0), '#16a34a'],
            ['Outstanding', $rs($summary['outstanding'] ?? 0), '#b45309'],
            ['Overdue (' . ($summary['overdue_count'] ?? 0) . ')', $rs($summary['overdue_amount'] ?? 0), '#dc2626'],
        ] as [$label, $value, $color])
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $label }}</p>
                <p class="mt-1 text-2xl font-bold" style="color: {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    {{-- Collection snapshot --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Collected Today</p>
            <p class="mt-1 text-xl font-bold text-gray-800 dark:text-gray-100">{{ $rs($summary['collected_today'] ?? 0) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Collected This Month</p>
            <p class="mt-1 text-xl font-bold text-gray-800 dark:text-gray-100">{{ $rs($summary['collected_month'] ?? 0) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Paid Challans</p>
            <p class="mt-1 text-xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($summary['paid_count'] ?? 0) }}</p>
        </div>
    </div>

    {{-- Outstanding fees table --}}
    <div class="rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-white/10">
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100">Outstanding Fees (unpaid, oldest first)</h3>
            <span class="text-xs text-gray-500">{{ count($outstanding) }} challan(s), max 200 shown</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide text-gray-500 dark:border-white/10">
                        <th class="px-4 py-2">Challan</th>
                        <th class="px-4 py-2">Student</th>
                        <th class="px-4 py-2">Roll No.</th>
                        <th class="px-4 py-2 text-right">Balance</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Due Date</th>
                        <th class="px-4 py-2 text-right">Days Late</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($outstanding as $row)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="px-4 py-2 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $row['challan'] }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $row['student'] }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $row['roll'] }}</td>
                            <td class="px-4 py-2 text-right font-semibold text-amber-700 dark:text-amber-400">{{ $rs($row['balance']) }}</td>
                            <td class="px-4 py-2">
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $row['status'] === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($row['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-500">{{ $row['due'] }}</td>
                            <td class="px-4 py-2 text-right {{ $row['days_late'] > 0 ? 'font-semibold text-red-600' : 'text-gray-400' }}">
                                {{ $row['days_late'] > 0 ? $row['days_late'] : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">🎉 No outstanding fees — everything is collected.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
