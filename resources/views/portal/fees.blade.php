@extends('layouts.portal')
@section('title', 'Fee Status')
@section('content')

<div x-data="{ uploadModal: { open: false, url: '', challan: '', suggested: 0 } }">

{{-- Flash messages --}}
@if(session('proof_uploaded'))
<div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
  <svg class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
  {{ session('proof_uploaded') }}
</div>
@endif
@if(session('success'))
<div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
  <svg class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
  {{ session('success') }}
</div>
@endif

{{-- ── Summary cards ── --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
  @php
    $balanceOk = $summary['balance'] <= 0;
    $feeCards = [
      ['label' => 'Total Fee Due', 'value' => $summary['total_due'], 'tone' => 'neutral', 'icon' => 'M9 7h6M9 11h6M9 15h4M6 3h12a1 1 0 011 1v17l-3.5-2-2 2-2-2-2 2L6 21V4a1 1 0 011-1z'],
      ['label' => 'Total Paid',    'value' => $summary['total_paid'], 'tone' => 'good', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
      ['label' => 'Balance Due',   'value' => $summary['balance'], 'tone' => $balanceOk ? 'good' : 'bad', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    $toneClasses = [
      'neutral' => ['bg' => 'bg-stone-50', 'ring' => 'ring-stone-200', 'text' => 'text-stone-700', 'icon' => 'bg-stone-200 text-stone-600'],
      'good'    => ['bg' => 'bg-emerald-50', 'ring' => 'ring-emerald-200', 'text' => 'text-emerald-700', 'icon' => 'bg-emerald-100 text-emerald-600'],
      'bad'     => ['bg' => 'bg-rose-50', 'ring' => 'ring-rose-200', 'text' => 'text-rose-700', 'icon' => 'bg-rose-100 text-rose-600'],
    ];
  @endphp
  @foreach($feeCards as $fc)
    @php $t = $toneClasses[$fc['tone']]; @endphp
    <div class="rounded-2xl {{ $t['bg'] }} p-5 ring-1 {{ $t['ring'] }}">
      <div class="mb-3 flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wide text-stone-400">{{ $fc['label'] }}</span>
        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $t['icon'] }}">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $fc['icon'] }}"/></svg>
        </span>
      </div>
      <div class="text-2xl font-bold {{ $t['text'] }}">Rs. {{ number_format($fc['value'], 0) }}</div>
    </div>
  @endforeach
</div>

{{-- ── Generate a Fee Slip ── --}}
<div class="mb-6 rounded-2xl border border-stone-200 bg-white p-6">
  <div class="mb-4 flex items-start gap-3">
    <span class="flex h-10 w-10 flex-none items-center justify-center rounded-xl" style="background:color-mix(in srgb, var(--site-brand, #6b2d39) 12%, transparent); color:var(--site-brand, #6b2d39)">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    </span>
    <div>
      <h3 class="font-semibold text-stone-800">Generate a Fee Slip</h3>
      <p class="mt-0.5 text-xs text-stone-400">Pay your whole semester fee at once, or generate a slip for whatever amount you want to pay right now — the rest can be paid later as another installment.</p>
    </div>
  </div>

  @if($errors->has('amount'))
    <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-600">
      {{ $errors->first('amount') }}
    </div>
  @endif

  <form action="{{ route('portal.fees') }}" method="GET" class="mb-4">
    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-stone-400">Fee Type</label>
    <select name="fee_type" onchange="this.form.submit()"
            class="w-full max-w-xs rounded-lg border-stone-300 text-sm focus:border-stone-400 focus:ring-stone-400 sm:w-auto">
      @foreach($feeTypeOptions as $value => $label)
        <option value="{{ $value }}" @selected($slipFeeType === $value)>{{ $label }}</option>
      @endforeach
    </select>
  </form>

  <div class="mb-4 grid grid-cols-1 gap-3 rounded-xl bg-stone-50 p-4 sm:grid-cols-3">
    <div>
      <p class="text-[11px] font-semibold uppercase tracking-wide text-stone-400">Total for this semester</p>
      <p class="mt-0.5 text-lg font-bold text-stone-700">Rs. {{ number_format($invoice['total']) }}</p>
    </div>
    <div>
      <p class="text-[11px] font-semibold uppercase tracking-wide text-stone-400">Already invoiced</p>
      <p class="mt-0.5 text-lg font-bold text-stone-700">Rs. {{ number_format($invoice['already_invoiced']) }}</p>
    </div>
    <div>
      <p class="text-[11px] font-semibold uppercase tracking-wide text-stone-400">Available to invoice</p>
      <p class="mt-0.5 text-lg font-bold text-emerald-600">Rs. {{ number_format($invoice['available']) }}</p>
    </div>
  </div>

  @if($invoice['available'] > 0)
    <form action="{{ route('portal.fees.generate-slip') }}" method="POST" class="flex flex-wrap items-end gap-3">
      @csrf
      <input type="hidden" name="fee_type" value="{{ $slipFeeType }}">
      <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-stone-400">Amount to Pay Now (Rs.)</label>
        <input type="number" name="amount" step="0.01" min="1" max="{{ $invoice['available'] }}"
               placeholder="e.g. 10000" required
               class="w-44 rounded-lg border-stone-300 text-sm focus:border-stone-400 focus:ring-stone-400">
      </div>
      <button type="submit"
              class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
              style="background:var(--site-brand, #6b2d39)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Generate Slip
      </button>
    </form>
  @else
    <p class="text-xs text-stone-400">This semester's {{ strtolower($feeTypeOptions[$slipFeeType] ?? 'fee') }} has been fully invoiced. Check your challans below for outstanding balances.</p>
  @endif
</div>

{{-- ── Fee challans table ── --}}
@if($payments->isEmpty())
<div class="rounded-2xl border border-stone-200 bg-white p-16 text-center">
  <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-stone-100">
    <svg class="h-7 w-7 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
  </div>
  <h3 class="mb-1 font-semibold text-stone-500">No Fee Records</h3>
  <p class="text-sm text-stone-400">Your fee challans will appear here once generated.</p>
</div>
@else
<div class="rounded-2xl border border-stone-200 bg-white">
  <div class="border-b border-stone-100 px-6 py-4">
    <h3 class="font-semibold text-stone-800">Fee Challans</h3>
    <p class="mt-0.5 text-xs text-stone-400">{{ $payments->count() }} challan(s) on record</p>
  </div>

  {{-- Card layout on small screens, table on larger — avoids horizontal scrolling on mobile --}}
  <div class="divide-y divide-stone-100 lg:hidden">
    @foreach($payments as $p)
      @php
        $status = $p->payment_status instanceof \BackedEnum ? $p->payment_status->value : ($p->payment_status ?? 'pending');
        $statusClasses = match($status) {
          'paid'    => 'bg-emerald-100 text-emerald-700',
          'overdue' => 'bg-rose-100 text-rose-700',
          'partial' => 'bg-amber-100 text-amber-700',
          default   => 'bg-stone-100 text-stone-600',
        };
        $canUpload = in_array($status, ['pending','overdue','partial']);
        $hasProof  = !empty($p->payment_proof_path);
        $awaitingVerification = $hasProof && $status !== 'paid';
      @endphp
      <div class="p-5">
        <div class="mb-2 flex items-center justify-between">
          <span class="font-mono text-xs font-semibold text-stone-600">{{ $p->challan_number }}</span>
          <span class="rounded-full px-2.5 py-1 text-xs font-bold capitalize {{ $statusClasses }}">{{ $status }}</span>
        </div>
        <p class="text-sm text-stone-500">
          {{ $p->fee_type instanceof \BackedEnum ? ucfirst(str_replace('_',' ',$p->fee_type->value)) : ucfirst(str_replace('_',' ',$p->fee_type ?? '')) }}
          @if($p->semester_number) &middot; Sem {{ $p->semester_number }} @endif
        </p>
        <div class="mt-3 grid grid-cols-3 gap-2 text-sm">
          <div><p class="text-[10px] uppercase text-stone-400">Amount</p><p class="font-semibold text-stone-700">{{ number_format($p->amount_due, 0) }}</p></div>
          <div><p class="text-[10px] uppercase text-stone-400">Paid</p><p class="font-semibold text-emerald-600">{{ number_format($p->amount_paid ?? 0, 0) }}</p></div>
          <div><p class="text-[10px] uppercase text-stone-400">Due</p><p class="font-semibold text-stone-500">{{ $p->due_date?->format('d M') ?? '—' }}</p></div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2">
          <a href="{{ route('portal.fees.challan.preview', $p) }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold" style="color:#6b2d39;background:#fdf3f4">View</a>
          <a href="{{ route('portal.fees.challan', $p) }}" target="_blank" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50">PDF</a>
          @if($canUpload)
            <button type="button"
                    @click="uploadModal = { open: true, url: '{{ route('portal.fees.proof', $p) }}', challan: '{{ $p->challan_number }}', suggested: {{ (float) ($p->balance ?? $p->amount_due) }} }"
                    class="rounded-lg bg-violet-50 px-3 py-1.5 text-xs font-semibold text-violet-700">
              {{ $hasProof ? 'Replace Proof' : 'Upload Proof' }}
            </button>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <div class="hidden overflow-x-auto lg:block">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-stone-200 bg-stone-50">
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-400">Challan #</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-400">Fee Type</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-400">Semester</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-stone-400">Amount</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-stone-400">Paid</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-stone-400">Fine</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-stone-400">Due Date</th>
          <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-stone-400">Status</th>
          <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-stone-400">Slip</th>
          <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-stone-400">Proof</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payments as $p)
        @php
          $status = $p->payment_status instanceof \BackedEnum ? $p->payment_status->value : ($p->payment_status ?? 'pending');
          $statusClasses = match($status) {
            'paid'    => 'bg-emerald-100 text-emerald-700',
            'overdue' => 'bg-rose-100 text-rose-700',
            'partial' => 'bg-amber-100 text-amber-700',
            default   => 'bg-stone-100 text-stone-600',
          };
          $canUpload = in_array($status, ['pending','overdue','partial']);
          $hasProof  = !empty($p->payment_proof_path);
          $awaitingVerification = $hasProof && $status !== 'paid';
        @endphp
        <tr class="border-b border-stone-50 transition-colors hover:bg-stone-50">
          <td class="px-5 py-3.5"><span class="font-mono text-xs font-semibold text-stone-600">{{ $p->challan_number }}</span></td>
          <td class="px-5 py-3.5 text-sm text-stone-600">
            {{ $p->fee_type instanceof \BackedEnum ? ucfirst(str_replace('_',' ',$p->fee_type->value)) : ucfirst(str_replace('_',' ',$p->fee_type ?? '')) }}
          </td>
          <td class="px-5 py-3.5 text-sm text-stone-500">{{ $p->semester_number ? 'Sem ' . $p->semester_number : '—' }}</td>
          <td class="px-5 py-3.5 text-right font-semibold text-stone-700">{{ number_format($p->amount_due, 0) }}</td>
          <td class="px-5 py-3.5 text-right font-semibold text-emerald-600">{{ number_format($p->amount_paid ?? 0, 0) }}</td>
          <td class="px-5 py-3.5 text-right text-sm {{ ($p->fine_amount ?? 0) > 0 ? 'text-rose-600' : 'text-stone-300' }}">
            {{ ($p->fine_amount ?? 0) > 0 ? number_format($p->fine_amount, 0) : '—' }}
          </td>
          <td class="px-5 py-3.5 text-sm text-stone-400">{{ $p->due_date?->format('d M Y') ?? '—' }}</td>
          <td class="px-5 py-3.5 text-center">
            <span class="rounded-full px-2.5 py-1 text-xs font-bold capitalize {{ $statusClasses }}">{{ $status }}</span>
          </td>
          <td class="px-5 py-3.5 text-center">
            <div class="inline-flex items-center gap-1.5">
              <a href="{{ route('portal.fees.challan.preview', $p) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition" style="color:#6b2d39;background:#fdf3f4">View</a>
              <a href="{{ route('portal.fees.challan', $p) }}" target="_blank" class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700">PDF</a>
              @if($status === 'paid' && $hasProof)
                <a href="{{ asset('storage/' . $p->payment_proof_path) }}" target="_blank" class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-700" title="View uploaded proof">Proof</a>
              @endif
            </div>
          </td>
          <td class="px-5 py-3.5 text-center align-middle">
            @if($canUpload)
              <div class="flex flex-col items-center gap-1.5">
                @if($hasProof)
                  @if($awaitingVerification)
                    <span class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700"
                          title="You claimed Rs. {{ number_format((float) $p->proof_claimed_amount) }} on {{ $p->proof_claimed_date?->format('d M Y') }} — awaiting admin confirmation">
                      Verification Pending
                    </span>
                  @else
                    <span class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Proof Uploaded</span>
                  @endif
                @endif
                <button type="button"
                        @click="uploadModal = { open: true, url: '{{ route('portal.fees.proof', $p) }}', challan: '{{ $p->challan_number }}', suggested: {{ (float) ($p->balance ?? $p->amount_due) }} }"
                        class="rounded-lg bg-violet-50 px-2.5 py-1.5 text-xs font-semibold text-violet-700 transition hover:bg-violet-100">
                  {{ $hasProof ? 'Replace' : 'Upload Proof' }}
                </button>
              </div>
            @else
              <span class="text-xs text-stone-300">—</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if($summary['total_fine'] > 0)
  <div class="border-t border-amber-200 bg-amber-50 px-6 py-3 text-xs text-amber-700">
    <strong>Note:</strong> Total late fine included: Rs. {{ number_format($summary['total_fine'], 0) }}
  </div>
  @endif

  <div class="border-t border-sky-100 bg-sky-50 px-6 py-3 text-xs text-sky-700">
    <strong>How to pay:</strong> Download the challan PDF, pay at any KCBL branch, then upload the bank-stamped receipt using <strong>Upload Proof</strong>. Admin will verify and mark your fee as paid.
  </div>
</div>
@endif

{{-- ── Upload Proof modal (shared by every row) ── --}}
<div x-show="uploadModal.open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     @keydown.escape.window="uploadModal.open = false">
  <div @click.outside="uploadModal.open = false"
       class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl"
       x-transition:enter="transition ease-out duration-150"
       x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
    <div class="mb-4 flex items-start justify-between">
      <div>
        <h3 class="font-semibold text-stone-800">Upload Payment Proof</h3>
        <p class="mt-0.5 font-mono text-xs text-stone-400" x-text="uploadModal.challan"></p>
      </div>
      <button type="button" @click="uploadModal.open = false" class="text-stone-400 transition hover:text-stone-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <form :action="uploadModal.url" method="POST" enctype="multipart/form-data" class="space-y-3">
      @csrf
      <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-stone-500">Amount Deposited (Rs.)</label>
        <input type="number" name="amount" step="0.01" min="1" required
               :placeholder="uploadModal.suggested ? uploadModal.suggested.toFixed(0) : 'e.g. 10000'"
               class="block w-full rounded-lg border-stone-300 text-sm focus:border-stone-400 focus:ring-stone-400">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-stone-500">Deposit Date</label>
        <input type="date" name="deposit_date" max="{{ now()->toDateString() }}" required
               class="block w-full rounded-lg border-stone-300 text-sm focus:border-stone-400 focus:ring-stone-400">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-stone-500">Receipt / Proof File</label>
        <input type="file" name="proof" accept="image/*,application/pdf" required
               class="block w-full text-xs text-stone-600 file:mr-2 file:rounded-lg file:border-0 file:bg-violet-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-violet-700 hover:file:bg-violet-100">
        <p class="mt-1 text-[11px] text-stone-400">JPG, PNG, or PDF — max 5MB.</p>
      </div>
      <div class="flex items-center gap-2 pt-2">
        <button type="submit" class="flex-1 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-700">
          Upload Proof
        </button>
        <button type="button" @click="uploadModal.open = false" class="rounded-lg px-4 py-2.5 text-sm font-medium text-stone-500 transition hover:bg-stone-100">
          Cancel
        </button>
      </div>
    </form>
  </div>
</div>

</div>

@endsection
