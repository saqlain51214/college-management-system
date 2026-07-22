@extends('layouts.public')
@section('title', 'Download Fee Challan — ' . ($college->college_name ?? 'JDCA'))

@section('content')
<div style="padding-top: var(--site-header-offset, 6rem);">
    <section class="py-12 sm:py-16" style="background:var(--site-body-bg)">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">

            <div class="mb-8 text-center">
                <h1 class="font-display text-2xl sm:text-3xl font-bold text-stone-900">Download Fee Challan</h1>
                <p class="mt-2 text-sm text-stone-500">Enter your Registration or Roll Number to view your dues and download or generate a fee challan.</p>
            </div>

            {{-- Lookup form --}}
            <form method="POST" action="{{ route('fee-challan.lookup') }}"
                  class="mx-auto flex max-w-xl flex-col gap-3 sm:flex-row rounded-2xl bg-white p-4 ring-1 ring-stone-200">
                @csrf
                <input type="text" name="identifier" value="{{ $identifier ?? old('identifier') }}" required
                       placeholder="e.g. JDCA-2026-0001 or REG-2026-0001"
                       class="flex-1 rounded-lg border border-stone-300 px-4 py-2.5 text-sm outline-none focus:border-[var(--site-brand)] focus:ring-2 focus:ring-[var(--site-brand)]/20">
                <button type="submit"
                        class="rounded-lg px-6 py-2.5 text-sm font-bold text-white transition hover:opacity-90"
                        style="background:var(--site-brand)">
                    Find Challan
                </button>
            </form>
            @error('identifier')<p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>@enderror

            @if(!empty($slipSuccess))
                <div class="mx-auto mt-4 max-w-xl rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-center text-sm font-semibold text-emerald-700">
                    {{ $slipSuccess }}
                </div>
            @endif

            {{-- Results --}}
            @if(!empty($searched))
                @if(!empty($notFound))
                    <div class="mx-auto mt-8 max-w-xl rounded-xl border border-amber-200 bg-amber-50 p-5 text-center">
                        <p class="text-sm font-semibold text-amber-800">No student found for “{{ $identifier }}”.</p>
                        <p class="mt-1 text-xs text-amber-700">Please check your Registration/Roll Number and try again, or contact the accounts office.</p>
                    </div>
                @elseif(!empty($result))
                    @php $student = $result['student']; $unpaid = $result['unpaid']; $paid = $result['paidThisMonth']; $byMonth = $result['byMonth']; @endphp

                    <div class="mt-8 rounded-2xl bg-white p-5 ring-1 ring-stone-200">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-2 border-b border-stone-100 pb-3">
                            <div>
                                <p class="font-display text-lg font-bold text-stone-900">{{ $student->name }}</p>
                                <p class="text-xs text-stone-500">Reg No: {{ $student->registration_number ?: $student->roll_number }}</p>
                            </div>
                        </div>

                        {{-- Semester fee summary --}}
                        @if($invoice)
                        <div class="mb-5 flex flex-wrap gap-4 rounded-xl bg-stone-50 p-3 text-xs">
                            <span class="text-stone-500">Total Semester Fee: <b class="text-stone-800">Rs. {{ number_format($invoice['total']) }}</b></span>
                            <span class="text-stone-500">Already Invoiced: <b class="text-stone-800">Rs. {{ number_format($invoice['already_invoiced']) }}</b></span>
                            <span class="text-stone-500">Available to Generate: <b class="text-emerald-700">Rs. {{ number_format($invoice['available']) }}</b></span>
                        </div>
                        @endif

                        {{-- Generate a new slip for a chosen amount --}}
                        <div class="mb-6 rounded-xl border border-stone-200 p-4">
                            <h3 class="mb-1 text-sm font-bold text-stone-800">Generate a Fee Slip</h3>
                            <p class="mb-3 text-xs text-stone-500">Pay the full semester at once, or generate a slip for whatever amount you want to deposit right now.</p>

                            @if(!empty($slipError))
                                <div class="mb-3 rounded-lg border border-red-200 bg-red-50 p-3 text-xs font-medium text-red-700">
                                    {{ $slipError }}
                                </div>
                            @endif

                            @if($invoice && $invoice['available'] > 0)
                                <form method="POST" action="{{ route('fee-challan.generate-slip') }}" class="flex flex-wrap items-end gap-3">
                                    @csrf
                                    <input type="hidden" name="identifier" value="{{ $identifier }}">
                                    <div>
                                        <label class="mb-1 block text-[11px] font-medium uppercase tracking-wide text-stone-500">Amount (Rs.)</label>
                                        <input type="number" name="amount" step="0.01" min="1" max="{{ $invoice['available'] }}" required
                                               placeholder="e.g. 10000"
                                               class="w-40 rounded-lg border border-stone-300 px-3 py-2 text-sm">
                                    </div>
                                    <button type="submit"
                                            class="rounded-lg px-5 py-2 text-sm font-bold text-white transition hover:opacity-90"
                                            style="background:var(--site-brand)">
                                        Generate Slip
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-stone-400">This semester's fee has been fully invoiced already — check "Payable Challans" below for outstanding balances.</p>
                            @endif
                        </div>

                        {{-- Unpaid / due challans --}}
                        <h3 class="mb-2 text-sm font-bold text-stone-800">Payable Challans</h3>
                        @forelse($unpaid as $p)
                            <div class="mb-2 flex flex-wrap items-center justify-between gap-2 rounded-lg border border-stone-200 p-3">
                                <div class="text-sm">
                                    <span class="font-mono text-xs text-stone-500">{{ $p->challan_number }}</span>
                                    <span class="ml-2 font-semibold text-stone-800">Rs. {{ number_format($p->net_amount) }}</span>
                                    <span class="ml-2 rounded-full px-2 py-0.5 text-[11px] font-medium {{ $p->payment_status?->value === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-stone-100 text-stone-600' }}">
                                        {{ ucfirst($p->payment_status?->value ?? 'pending') }}
                                    </span>
                                    @if($p->due_date)<span class="ml-2 text-xs text-stone-400">Due: {{ $p->due_date->format('d M Y') }}</span>@endif
                                    <span class="ml-2 text-xs text-stone-400">Installment #{{ $p->installment_no }}</span>
                                </div>
                                <a href="{{ route('fee-challan.pdf', ['payment' => $p->id, 'ref' => $student->roll_number]) }}" target="_blank"
                                   class="rounded-lg px-4 py-1.5 text-xs font-bold text-white transition hover:opacity-90" style="background:var(--site-brand)">
                                    Download PDF
                                </a>
                            </div>
                        @empty
                            <p class="rounded-lg bg-emerald-50 p-3 text-sm text-emerald-700">🎉 No pending challans — your fees are clear.</p>
                        @endforelse

                        {{-- Paid this month --}}
                        @if($paid->isNotEmpty())
                            <h3 class="mb-2 mt-5 text-sm font-bold text-stone-800">Paid This Month</h3>
                            @foreach($paid as $p)
                                <div class="mb-2 flex flex-wrap items-center justify-between gap-2 rounded-lg border border-emerald-200 bg-emerald-50/40 p-3">
                                    <div class="text-sm">
                                        <span class="font-mono text-xs text-stone-500">{{ $p->challan_number }}</span>
                                        @if($p->receipt_number)<span class="ml-2 font-mono text-[11px] text-emerald-700">{{ $p->receipt_number }}</span>@endif
                                        <span class="ml-2 font-semibold text-stone-800">Rs. {{ number_format($p->amount_paid) }}</span>
                                        <span class="ml-2 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-medium text-emerald-700">Paid</span>
                                    </div>
                                    <a href="{{ route('fee-challan.pdf', ['payment' => $p->id, 'ref' => $student->roll_number]) }}" target="_blank"
                                       class="rounded-lg border border-stone-300 px-4 py-1.5 text-xs font-bold text-stone-700 transition hover:bg-stone-50">
                                        Download Receipt
                                    </a>
                                </div>
                            @endforeach
                        @endif

                        {{-- All challans, month-wise --}}
                        @if($byMonth->isNotEmpty())
                            <h3 class="mb-2 mt-5 text-sm font-bold text-stone-800">All Challans — Month-wise</h3>
                            <div class="space-y-3">
                                @foreach($byMonth as $month => $items)
                                    <div>
                                        <p class="mb-1.5 text-[11px] font-bold uppercase tracking-wide text-stone-400">{{ $month }}</p>
                                        <div class="space-y-1.5">
                                            @foreach($items as $p)
                                                <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg bg-stone-50 px-3 py-2 text-xs">
                                                    <span class="font-mono text-stone-500">{{ $p->challan_number }}</span>
                                                    <span class="font-semibold text-stone-700">Rs. {{ number_format($p->net_amount) }}</span>
                                                    <span class="rounded-full px-2 py-0.5 font-medium {{ $p->payment_status?->value === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-200 text-stone-600' }}">
                                                        {{ ucfirst($p->payment_status?->value ?? 'pending') }}
                                                    </span>
                                                    <a href="{{ route('fee-challan.pdf', ['payment' => $p->id, 'ref' => $student->roll_number]) }}" target="_blank"
                                                       class="font-semibold" style="color:var(--site-brand)">PDF</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endif

        </div>
    </section>
</div>
@endsection
