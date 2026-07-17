<x-filament-panels::page>

    {{-- Summary bar --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <div class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold"
             style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            {{ $newCount }} New Students
        </div>
        <div class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold"
             style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            {{ $updateCount }} Updates
        </div>
        <div class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold"
             style="background:#f9fafb;border:1px solid #e5e7eb;color:#6b7280;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            {{ $totalCount }} Total Rows
        </div>
        <div class="ml-auto flex items-center gap-1.5 rounded-xl px-4 py-2.5 text-xs font-medium"
             style="background:#fffbeb;border:1px solid #fde68a;color:#92400e;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            Preview expires in 30 min — confirm or cancel now
        </div>
    </div>

    {{-- Data table --}}
    <div class="rounded-2xl overflow-hidden" style="border:1px solid #e5e7eb;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;width:48px;">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Father's Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Reg. Number</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Roll Number</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Program</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Session</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Gender</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color:#9ca3af;">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $i => $row)
                    @php
                        $isNew    = ($row['_status'] ?? 'new') === 'new';
                        $rowBg    = $loop->even ? '#ffffff' : '#fafafa';
                        $statusStyle = $isNew
                            ? 'background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;'
                            : 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;';
                        $statusLabel = $isNew ? 'New' : 'Update';
                    @endphp
                    <tr style="background:{{ $rowBg }};border-bottom:1px solid #f3f4f6;">
                        <td class="px-4 py-2.5 text-xs font-mono" style="color:#9ca3af;">
                            {{ $row['_row'] ?? ($i + 2) }}
                        </td>
                        <td class="px-4 py-2.5">
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold" style="{{ $statusStyle }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 font-medium text-gray-800">
                            {{ $row['name'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-gray-600">
                            {{ $row['father_name'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 font-mono text-xs text-gray-500">
                            {{ $row['registration_number'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 font-mono text-xs text-gray-500">
                            {{ $row['roll_number'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-gray-600 text-xs">
                            {{ $row['_program_name'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-gray-500 text-xs">
                            {{ $row['batch_year'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-gray-500 text-xs">
                            {{ $row['gender'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-gray-500 text-xs font-mono">
                            {{ $row['phone'] ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer note --}}
        <div class="px-5 py-3 text-xs" style="background:#f9fafb;border-top:1px solid #e5e7eb;color:#6b7280;">
            Review the rows above. Click <strong>Confirm &amp; Import</strong> in the top-right to save, or <strong>Cancel</strong> to discard.
            Fields not shown (CNIC, city, address, semester, remarks) are also captured and will be saved.
        </div>
    </div>

</x-filament-panels::page>
