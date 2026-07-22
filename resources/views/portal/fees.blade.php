@extends('layouts.portal')
@section('title', 'Fee Status')
@section('content')

{{-- Flash: proof uploaded / slip generated --}}
@if(session('proof_uploaded'))
<div class="mb-4 flex items-start gap-3 rounded-xl px-4 py-3 text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;">
  <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
  {{ session('proof_uploaded') }}
</div>
@endif
@if(session('success'))
<div class="mb-4 flex items-start gap-3 rounded-xl px-4 py-3 text-sm font-medium" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;">
  <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
  {{ session('success') }}
</div>
@endif

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

{{-- Generate a fee slip — pay the full semester at once, or in installments of your choice --}}
<div class="bg-white rounded-2xl p-5 mb-6" style="border: 1px solid #e5e7eb">
  <h3 class="font-semibold text-gray-800 mb-1">Generate a Fee Slip</h3>
  <p class="text-xs text-gray-400 mb-3">Pay your whole semester fee at once, or generate a slip for whatever amount you want to pay right now — the rest can be paid later as another installment.</p>

  @if($errors->has('amount'))
    <div class="mb-3 rounded-lg px-3 py-2 text-xs font-medium" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca">
      {{ $errors->first('amount') }}
    </div>
  @endif

  <form action="{{ route('portal.fees') }}" method="GET" class="flex flex-wrap items-end gap-3 mb-3">
    <div>
      <label class="block text-xs font-medium uppercase tracking-wide text-gray-500 mb-1">Fee Type</label>
      <select name="fee_type" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm">
        @foreach($feeTypeOptions as $value => $label)
          <option value="{{ $value }}" @selected($slipFeeType === $value)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </form>

  <div class="flex flex-wrap gap-4 rounded-lg px-3 py-2 text-xs mb-3" style="background:#f9fafb">
    <span class="text-gray-500">Total for this semester: <b class="text-gray-800">Rs. {{ number_format($invoice['total']) }}</b></span>
    <span class="text-gray-500">Already invoiced: <b class="text-gray-800">Rs. {{ number_format($invoice['already_invoiced']) }}</b></span>
    <span class="text-gray-500">Available to invoice: <b style="color:#15803d">Rs. {{ number_format($invoice['available']) }}</b></span>
  </div>

  @if($invoice['available'] > 0)
    <form action="{{ route('portal.fees.generate-slip') }}" method="POST" class="flex flex-wrap items-end gap-3">
      @csrf
      <input type="hidden" name="fee_type" value="{{ $slipFeeType }}">
      <div>
        <label class="block text-xs font-medium uppercase tracking-wide text-gray-500 mb-1">Amount to Pay Now (Rs.)</label>
        <input type="number" name="amount" step="0.01" min="1" max="{{ $invoice['available'] }}"
               placeholder="e.g. 10000" required
               class="w-40 rounded-lg border-gray-300 text-sm">
      </div>
      <button type="submit"
              class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-white transition"
              style="background:#15803d">
        Generate Slip
      </button>
    </form>
  @else
    <p class="text-xs text-gray-400">This semester's {{ strtolower($feeTypeOptions[$slipFeeType] ?? 'fee') }} has been fully invoiced. Check your challans below for outstanding balances.</p>
  @endif
</div>

@if($payments->isEmpty())
<div class="bg-white rounded-2xl p-16 text-center" style="border: 1px solid #e5e7eb">
  <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #f3f4f6">
    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
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
          <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide" style="color: #9ca3af">Upload Proof</th>
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
          $canUpload = in_array($status, ['pending','overdue','partial']);
          $hasProof  = !empty($p->payment_proof_path);
          $awaitingVerification = $hasProof && $status !== 'paid';
        @endphp

        {{-- x-data on the <tr> so both the button and form share the same showUpload state --}}
        <tr class="hover:bg-gray-50 transition-colors" style="border-bottom: 1px solid #f9fafb"
            x-data="{ showUpload: false }">
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

          {{-- PDF column: View preview + Download PDF --}}
          <td class="px-5 py-3.5 text-center">
            <div class="inline-flex items-center gap-1.5">
              <a href="{{ route('portal.fees.challan.preview', $p) }}"
                 class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition"
                 style="color:#6b2d39;background:#fdf3f4"
                 onmouseover="this.style.background='#f9e0e3'" onmouseout="this.style.background='#fdf3f4'">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View
              </a>
              <a href="{{ route('portal.fees.challan', $p) }}" target="_blank"
                 class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition"
                 style="color:#1e3a5f;background:#eff6ff"
                 onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                PDF
              </a>
              @if($status === 'paid' && $hasProof)
                <a href="{{ asset('storage/' . $p->payment_proof_path) }}" target="_blank"
                   class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1.5 rounded-lg"
                   style="color:#15803d;background:#f0fdf4;border:1px solid #bbf7d0;"
                   title="View uploaded proof">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Proof
                </a>
              @endif
            </div>
          </td>

          {{-- Upload Proof column --}}
          <td class="px-5 py-3.5 text-center align-top">
            @if($canUpload)
              <div class="flex flex-col items-center gap-2">

                {{-- State: proof already on file --}}
                @if($hasProof)
                  @if($awaitingVerification)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1.5 rounded-lg"
                          style="color:#92400e;background:#fffbeb;border:1px solid #fde68a;"
                          title="You claimed Rs. {{ number_format((float) $p->proof_claimed_amount) }} on {{ $p->proof_claimed_date?->format('d M Y') }} — awaiting admin confirmation">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      Verification Pending
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1.5 rounded-lg"
                          style="color:#15803d;background:#f0fdf4;border:1px solid #bbf7d0;">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                      Proof Uploaded
                    </span>
                  @endif
                  {{-- Allow replacing the proof --}}
                  <button @click="showUpload = !showUpload"
                          class="text-xs font-medium px-2 py-1 rounded-lg transition"
                          style="color:#6b7280;background:#f3f4f6"
                          onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'"
                          type="button">
                    <span x-text="showUpload ? 'Cancel' : 'Replace'">Replace</span>
                  </button>
                @else
                  {{-- State: no proof yet — show Upload Proof button --}}
                  <button @click="showUpload = !showUpload"
                          class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition"
                          style="color:#7c3aed;background:#f5f3ff;border:1px solid #ddd6fe"
                          onmouseover="this.style.background='#ede9fe'" onmouseout="this.style.background='#f5f3ff'"
                          type="button">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span x-text="showUpload ? 'Cancel' : 'Upload Proof'">Upload Proof</span>
                  </button>
                @endif

                {{-- Inline upload form (Alpine toggle) --}}
                <div x-show="showUpload" x-cloak class="w-full min-w-[220px] text-left">
                  <form action="{{ route('portal.fees.proof', $p) }}" method="POST" enctype="multipart/form-data"
                        class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ddd6fe;">
                    @csrf
                    <p class="text-xs font-semibold mb-2" style="color:#7c3aed;">
                      Bank-Stamped Receipt
                    </p>
                    <label class="block text-[10px] font-medium uppercase tracking-wide text-gray-500 mb-1">Amount Deposited (Rs.)</label>
                    <input type="number" name="amount" step="0.01" min="1" required
                           placeholder="e.g. {{ number_format($p->balance ?? $p->amount_due, 0, '.', '') }}"
                           class="block w-full text-xs rounded-lg border-gray-300 mb-2">
                    <label class="block text-[10px] font-medium uppercase tracking-wide text-gray-500 mb-1">Deposit Date</label>
                    <input type="date" name="deposit_date" max="{{ now()->toDateString() }}" required
                           class="block w-full text-xs rounded-lg border-gray-300 mb-2">
                    <label class="block text-[10px] font-medium uppercase tracking-wide text-gray-500 mb-1">Receipt / Proof File</label>
                    <input type="file"
                           name="proof"
                           accept="image/*,application/pdf"
                           required
                           class="block w-full text-xs text-gray-600 mb-1 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    <p class="text-[10px] text-gray-400 mb-2">Upload bank-stamped challan (JPG, PNG, or PDF, max 5MB)</p>
                    <div class="flex items-center gap-2">
                      <button type="submit"
                              class="text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition"
                              style="background:#7c3aed"
                              onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                        Upload
                      </button>
                      <button type="button" @click="showUpload = false"
                              class="text-xs text-gray-400 hover:text-gray-600 transition">
                        Cancel
                      </button>
                    </div>
                  </form>
                </div>

              </div>
            @else
              {{-- Paid row with no proof — nothing actionable --}}
              <span class="text-xs text-gray-300">—</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Fine total note --}}
  @if($summary['total_fine'] > 0)
  <div class="px-6 py-3 text-xs" style="background:#fffbeb;border-top:1px solid #fde68a;color:#92400e">
    <strong>Note:</strong> Total late fine included: Rs. {{ number_format($summary['total_fine'], 0) }}
  </div>
  @endif

  {{-- Upload instructions note --}}
  <div class="px-6 py-3 text-xs" style="background:#f0f9ff;border-top:1px solid #bae6fd;color:#0369a1;">
    <strong>How to pay:</strong> Download the challan PDF, pay at any KCBL branch, then upload the bank-stamped receipt using <strong>Upload Proof</strong>. Admin will verify and mark your fee as paid.
  </div>
</div>
@endif

@endsection
