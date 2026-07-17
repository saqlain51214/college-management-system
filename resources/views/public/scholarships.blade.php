@extends('layouts.public')
@section('title', 'Scholarships')

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Scholarships</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Scholarships</h1>
            <p class="text-white/70 text-sm mt-1">Financial support programs for deserving students</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-5xl px-4 sm:px-6 py-12">
    <p class="text-stone-600 text-center max-w-2xl mx-auto mb-10">
        JDCA is committed to making quality education accessible. We offer several scholarship programs to support talented and deserving students from Astore and surrounding areas.
    </p>

    <div class="grid sm:grid-cols-2 gap-6">
        @foreach([
            ['merit','Merit-Based Scholarship','bg-blue-600','For students who demonstrate exceptional academic performance.','Awarded to top-performing students based on their annual/semester results. Covers partial or full tuition fee for the subsequent semester.','M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['need','Need-Based Scholarship','bg-green-600','For students from financially disadvantaged backgrounds.','Provides financial assistance to students who cannot afford to continue their education due to economic hardship. Requires income certificate and supporting documents.','M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['orphan','Orphan Scholarship','bg-purple-600','Full fee waiver for orphaned students.','Students who have lost their father or both parents are eligible for a full tuition fee waiver throughout their program, subject to annual review and documentation.','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['special','Special Category Scholarship','bg-amber-600','For students with special circumstances or disabilities.','Awarded to students with disabilities, special health conditions, or exceptional circumstances that impact their ability to pursue education independently. Each case is reviewed individually.','M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
        ] as [$type, $title, $colorClass, $tagline, $desc, $icon])
        <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-2 {{ $colorClass }}"></div>
            <div class="p-6">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $colorClass }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-stone-800 leading-snug">{{ $title }}</h2>
                        <p class="text-xs text-stone-500 mt-0.5">{{ $tagline }}</p>
                    </div>
                </div>
                <p class="text-sm text-stone-600 leading-relaxed mb-4">{{ $desc }}</p>
                <a href="{{ route('scholarships.show', $type) }}"
                   class="inline-flex items-center gap-1 text-sm font-semibold transition-colors" style="color:var(--site-brand)">
                    View Details →
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-10 rounded-2xl p-6 text-center" style="background:var(--site-brand)">
        <h3 class="text-white font-bold text-lg mb-2">How to Apply for a Scholarship</h3>
        <p class="text-white/80 text-sm max-w-xl mx-auto mb-5">Submit a scholarship application along with your admission form or at any time during your studies. Applications are reviewed by the scholarship committee.</p>
        <a href="{{ route('admissions') }}" class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold bg-white transition hover:bg-stone-100" style="color:var(--site-brand)">
            Apply for Admission →
        </a>
    </div>
</div>
@endsection
