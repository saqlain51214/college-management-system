@extends('layouts.public')
@section('title', 'Fee Structure')

@section('content')
@php $customBody = !empty($cmsPage) ? $cmsPage->customBodyHtml() : null; @endphp
@if($customBody)
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span class="mx-1.5">›</span><span class="text-white/90">{{ $cmsPage->title }}</span></p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $cmsPage->title }}</h1>
        </div>
    </div>
</div>
<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12"><div class="prose-content max-w-none">{!! $customBody !!}</div></div>
@else
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('admissions') }}" class="hover:text-white">Admission</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Fee Structure</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Fee Structure</h1>
            <p class="text-white/70 text-sm mt-1">Academic Year 2024–25</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12 space-y-10">

    @php
        $fmtFreq = fn ($f) => $f ? ucwords(str_replace('_', ' ', $f)) : '—';
        $fmtType = fn ($t) => ucwords(str_replace('_', ' ', $t instanceof \BackedEnum ? $t->value : (string) $t));
    @endphp

    @forelse(($feeGroups ?? collect()) as $programName => $items)
    <section>
        <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color:var(--site-brand)">{{ $programName }}</h2>
        <div class="overflow-x-auto rounded-xl border border-stone-200 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:var(--site-brand)" class="text-white">
                        <th class="px-5 py-3 text-left font-semibold">Fee Head</th>
                        <th class="px-5 py-3 text-left font-semibold">Frequency</th>
                        <th class="px-5 py-3 text-right font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($items as $i => $fee)
                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-stone-50' }} hover:bg-blue-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-stone-800">{{ $fee->title ?: $fmtType($fee->fee_type) }}{{ $fee->semester_number ? ' (Semester '.$fee->semester_number.')' : '' }}</td>
                        <td class="px-5 py-3 text-stone-500">{{ $fmtFreq($fee->frequency) }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-stone-800">Rs. {{ number_format((float) $fee->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @empty
    <div class="rounded-2xl border border-dashed border-stone-200 bg-white px-6 py-16 text-center">
        <p class="text-stone-500">Fee structure will be published here soon. Please contact the accounts office for details.</p>
    </div>
    @endforelse

    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 flex items-start gap-4">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm text-amber-800 space-y-1">
            <p class="font-semibold">Important Notes:</p>
            <ul class="list-disc list-inside space-y-0.5 text-amber-700">
                <li>Fee structure may be revised by the university. Please confirm at the time of admission.</li>
                <li>Fee once paid is non-refundable.</li>
                <li>Scholarship holders may have partial or full fee waiver as per scholarship terms.</li>
                <li>For exact fee breakdown, contact the accounts office.</li>
            </ul>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('scholarships') }}" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold text-white shadow transition hover:opacity-90" style="background:var(--site-gold)">
            View Scholarship Opportunities →
        </a>
    </div>
</div>
@endif
@endsection
