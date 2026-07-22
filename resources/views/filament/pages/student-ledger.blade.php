<x-filament-panels::page>
    @php $rs = fn ($n) => 'Rs. ' . number_format((float) $n); @endphp

    {{-- Search bar --}}
    <form wire:submit.prevent="search"
          class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">
                Registration Number or Roll Number
            </label>
            <input type="text" wire:model="q" autofocus
                   placeholder="e.g. 4267 or JDCA-2026-0224"
                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-gray-800 dark:text-gray-100"/>
        </div>
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
            </svg>
            <span wire:loading.remove wire:target="search">Search</span>
            <span wire:loading wire:target="search">Searching…</span>
        </button>
    </form>

    {{-- Not found --}}
    @if ($searched && $notFound)
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-6 text-center text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
            {{ $notFound }}
        </div>
    @endif

    @if ($student)
        {{-- Student profile --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-gray-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">{{ $student['name'] }}</h2>
                    <p class="text-sm text-gray-500">S/D of {{ $student['father'] ?: '—' }}</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $student['active'] ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                    {{ $student['active'] ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-x-6 gap-y-3 text-sm sm:grid-cols-3 lg:grid-cols-4">
                @foreach ([
                    ['Reg No.', $student['reg'] ?: '—'],
                    ['Roll No.', $student['roll'] ?: '—'],
                    ['Programme', $student['program'] ?: '—'],
                    ['Department', $student['department'] ?: '—'],
                    ['Phone', $student['phone'] ?: '—'],
                    ['Gender', $student['gender'] ? ucfirst($student['gender']) : '—'],
                ] as [$label, $value])
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ $label }}</p>
                        <p class="mt-0.5 font-medium text-gray-800 dark:text-gray-100">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Financial summary --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @foreach ([
                ['Total Billed', $rs($totals['billed'] ?? 0), '#6b7280'],
                ['Total Paid', $rs($totals['paid'] ?? 0), '#16a34a'],
                ['Outstanding', $rs($totals['outstanding'] ?? 0), ($totals['outstanding'] ?? 0) > 0 ? '#dc2626' : '#16a34a'],
            ] as [$label, $value, $color])
                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $label }}</p>
                    <p class="mt-1 text-2xl font-bold" style="color: {{ $color }}">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        {{-- Generate Fee Slip (custom, self-chosen amount) --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
            <h3 class="mb-3 text-sm font-bold text-gray-800 dark:text-gray-100">Generate Fee Slip</h3>

            @php $slip = $this->slipSummary(); @endphp

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">Fee Type</label>
                    <select wire:model.live="slipFeeType" class="w-full rounded-lg border-gray-300 text-sm dark:border-white/10 dark:bg-gray-800 dark:text-gray-100">
                        @foreach ($this->feeTypeOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">Semester</label>
                    <select wire:model.live="slipSemester" class="w-full rounded-lg border-gray-300 text-sm dark:border-white/10 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Not applicable</option>
                        @foreach (range(1, 8) as $n)
                            <option value="{{ $n }}">Semester {{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">Academic Year</label>
                    <select wire:model.live="slipAcademicYearId" class="w-full rounded-lg border-gray-300 text-sm dark:border-white/10 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Not applicable</option>
                        @foreach ($this->academicYearOptions() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">Due Date</label>
                    <input type="date" wire:model="slipDueDate" class="w-full rounded-lg border-gray-300 text-sm dark:border-white/10 dark:bg-gray-800 dark:text-gray-100"/>
                </div>
            </div>

            @if ($slip)
                <div class="mt-3 flex flex-wrap gap-4 rounded-lg bg-gray-50 px-3 py-2 text-xs dark:bg-white/5">
                    <span class="text-gray-500">Total for period: <b class="text-gray-800 dark:text-gray-100">{{ $rs($slip['total']) }}</b></span>
                    <span class="text-gray-500">Already invoiced: <b class="text-gray-800 dark:text-gray-100">{{ $rs($slip['already_invoiced']) }}</b></span>
                    <span class="text-gray-500">Available to invoice: <b class="text-emerald-700 dark:text-emerald-400">{{ $rs($slip['available']) }}</b></span>
                </div>
            @endif

            <form wire:submit.prevent="generateSlip" class="mt-3 flex flex-wrap items-end gap-3">
                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-gray-500">Amount to Invoice (Rs.)</label>
                    <input type="number" step="0.01" min="0" wire:model="slipAmount"
                           placeholder="e.g. 10000"
                           class="w-40 rounded-lg border-gray-300 text-sm dark:border-white/10 dark:bg-gray-800 dark:text-gray-100"/>
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">
                    Generate Slip
                </button>
            </form>

            @if ($slipSuccess)
                <p class="mt-2 text-sm font-medium text-emerald-700 dark:text-emerald-400">{{ $slipSuccess }}</p>
            @endif
            @if ($slipError)
                <p class="mt-2 text-sm font-medium text-red-600">{{ $slipError }}</p>
            @endif
        </div>

        {{-- Challan ledger --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-white/10">
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100">Fee Challans</h3>
                <span class="text-xs text-gray-500">{{ $totals['count'] ?? 0 }} total · {{ $totals['unpaid'] ?? 0 }} with balance</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide text-gray-500 dark:border-white/10">
                            <th class="px-4 py-2">Challan</th>
                            <th class="px-4 py-2">Fee Type</th>
                            <th class="px-4 py-2">Sem / Inst.</th>
                            <th class="px-4 py-2 text-right">Payable</th>
                            <th class="px-4 py-2 text-right">Paid</th>
                            <th class="px-4 py-2 text-right">Balance</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Due</th>
                            <th class="px-4 py-2">Paid On</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $row)
                            <tr class="border-b border-gray-100 dark:border-white/5">
                                <td class="px-4 py-2 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $row['challan'] }}</td>
                                <td class="px-4 py-2 text-gray-700 dark:text-gray-200">{{ $row['fee_type'] }}</td>
                                <td class="px-4 py-2 text-gray-500">{{ $row['semester'] ? 'S' . $row['semester'] : '—' }} · #{{ $row['installment'] }}</td>
                                <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-200">{{ $rs($row['net']) }}</td>
                                <td class="px-4 py-2 text-right text-green-700 dark:text-green-400">{{ $rs($row['paid']) }}</td>
                                <td class="px-4 py-2 text-right font-semibold {{ $row['balance'] > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-gray-400' }}">{{ $rs($row['balance']) }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $badge = match ($row['status']) {
                                            'paid'    => 'bg-green-100 text-green-700',
                                            'partial' => 'bg-blue-100 text-blue-700',
                                            'overdue' => 'bg-red-100 text-red-700',
                                            'waived'  => 'bg-purple-100 text-purple-700',
                                            default   => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ ucfirst($row['status']) }}</span>
                                </td>
                                <td class="px-4 py-2 text-gray-500">
                                    {{ $row['due'] }}
                                    @if ($row['days_late'] > 0)
                                        <span class="ml-1 text-xs font-semibold text-red-600">({{ $row['days_late'] }}d late)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-500">{{ $row['paid_on'] }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('pdf.challan', $row['id']) }}" target="_blank"
                                       class="text-xs font-medium text-primary-600 hover:underline">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-gray-400">No fee challans for this student yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-filament-panels::page>
