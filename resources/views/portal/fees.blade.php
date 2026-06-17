@extends('layouts.portal')
@section('title', 'Fee Status')
@section('content')

{{-- Summary cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
  @php
  $feeCards = [
    ['label' => 'Total Fee Due',  'value' => 'Rs. ' . number_format($summary['total_due'],  0), 'color' => '#374151', 'bg' => '#f9fafb', 'border' => '#e5e7eb'],
    ['label' => 'Total Paid',     'value' => 'Rs. ' . number_format($summary['total_paid'], 0), 'color' => '#15803d', 'bg' => '#f0fdf4', 'border' => '#bbf7d0'],
    ['label' => 'Balance Due',    'value' => 'Rs. ' . number_format($summary['balance'],    0),
     'color' => $summary['balance'] > 0 ? '#dc2626' : '#15803d',
     'bg'    => $summary['balance'] > 0 ? '#fef2f2' : '#f0fdf4',
     'border'=> $summary['balance'] > 0 ? '#fecaca' : '#bbf7d0'],
  ];
  @endphp
  @foreach($feeCards as $fc)
  <div class="rounded-2xl p-5" style="background: {{ $fc['bg'] }}; border: 1px solid {{ $fc['border'] }}">
    <div class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #9ca3af">{{ $fc['label'] }}</div>
    <div class="text-2xl font-bold" style="color: {{ $fc['color'] }}">{{ $fc['value'] }}</div>
  </div>
  @endforeach
</div>

@if($payments->isEmpty())
<div class="bg-white rounded-2xl p-16 text-center" style="border: 1px solid #e5e7eb">
  <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #f3f4f6">
    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
  </div>
  <h3 class="font-semibold text-gray-500 mb-1">No Fee Records</h3>
  <p class="text-sm text-gray-400">Your fee challans will appear here once generated.</p>
</div>
@else
<div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
  <div class="px-6 py-4" style="border-bottom: 1px solid #f3f4f6">
    <h3 class="font-semibold text-gray-800">Fee Challans</h3>
    <p class="text-xs text-gray-400 mt-0.5">{{ $payments->count() }} challan(s) on record</p>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb">
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Challan #</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Fee Type</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Semester</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Amount</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Paid</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Fine</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Due Date</th>
          <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Status</th>
          <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">PDF</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payments as $p)
        @php
          $status = $p->payment_status instanceof \BackedEnum ? $p->payment_status->value : ($p->payment_status ?? 'pending');
          $statusStyle = match($status) {
            'paid'    => 'background:#dcfce7;color:#15803d',
            'overdue' => 'background:#fee2e2;color:#dc2626',
            'partial' => 'background:#fef3c7;color:#92400e',
            default   => 'background:#f3f4f6;color:#6b7280',
          };
        @endphp
        <tr class="hover:bg-gray-50 transition-colors" style="border-bottom: 1px solid #f9fafb">
          <td class="px-5 py-3.5">
            <span class="font-mono text-xs font-semibold text-gray-600">{{ $p->challan_number }}</span>
          </td>
          <td class="px-5 py-3.5 text-gray-600 text-sm">
            {{ $p->fee_type instanceof \BackedEnum ? ucfirst(str_replace('_',' ',$p->fee_type->value)) : ucfirst(str_replace('_',' ',$p->fee_type ?? '')) }}
          </td>
          <td class="px-5 py-3.5 text-gray-500 text-sm">
            {{ $p->semester_number ? 'Sem ' . $p->semester_number : '—' }}
          </td>
          <td class="px-5 py-3.5 text-right font-semibold text-gray-700">
            {{ number_format($p->amount_due, 0) }}
          </td>
          <td class="px-5 py-3.5 text-right font-semibold" style="color: #15803d">
            {{ number_format($p->amount_paid ?? 0, 0) }}
          </td>
          <td class="px-5 py-3.5 text-right text-sm" style="color: {{ ($p->fine_amount ?? 0) > 0 ? '#dc2626' : '#9ca3af' }}">
            {{ ($p->fine_amount ?? 0) > 0 ? number_format($p->fine_amount, 0) : '—' }}
          </td>
          <td class="px-5 py-3.5 text-sm text-gray-400">
            {{ $p->due_date?->format('d M Y') ?? '—' }}
          </td>
          <td class="px-5 py-3.5 text-center">
            <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize" style="{{ $statusStyle }}">
              {{ $status }}
            </span>
          </td>
          <td class="px-5 py-3.5 text-center">
            <a href="{{ route('portal.fees.challan', $p) }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition"
               style="color: #1e3a5f; background: #eff6ff"
               onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              PDF
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Fine total note --}}
  @if($summary['total_fine'] > 0)
  <div class="px-6 py-3 text-xs" style="background: #fffbeb; border-top: 1px solid #fde68a; color: #92400e">
    <strong>Note:</strong> Total late fine included: Rs. {{ number_format($summary['total_fine'], 0) }}
  </div>
  @endif
</div>
@endif

@endsection
