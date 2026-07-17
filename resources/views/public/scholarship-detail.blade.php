@extends('layouts.public')
@php
$scholarships = [
    'merit' => [
        'title'       => 'Merit-Based Scholarship',
        'tagline'     => 'Rewarding academic excellence',
        'color'       => 'bg-blue-600',
        'description' => 'The Merit-Based Scholarship is awarded to students who demonstrate outstanding academic performance in their previous examinations. This scholarship encourages a culture of excellence and motivates students to strive for the highest academic standards.',
        'eligibility' => ['Minimum 75% marks in last qualifying examination','Must be enrolled as a full-time student','No outstanding fee dues','Good disciplinary record'],
        'benefits'    => ['50% tuition fee waiver for the subsequent semester','Renewable each semester upon maintaining academic performance','Certificate of merit issued by the college'],
        'docs'        => ['Attested copy of last result/DMC','Original result verification letter','College enrollment confirmation','Application form (available at admin office)'],
    ],
    'need'   => [
        'title'       => 'Need-Based Scholarship',
        'tagline'     => 'Making education accessible for all',
        'color'       => 'bg-green-600',
        'description' => 'The Need-Based Scholarship is designed to ensure that financial hardship is never a barrier to education. Deserving students from low-income families can receive financial assistance to continue their studies at JDCA.',
        'eligibility' => ['Household income below Rs. 20,000/month','Must be enrolled as a full-time student','No pending disciplinary actions','Supported by local government income certificate'],
        'benefits'    => ['Up to 75% tuition fee waiver','Renewable annually upon review','Priority consideration for other welfare programs'],
        'docs'        => ['Income certificate from relevant authority','Attested copy of CNIC/Form-B of guardian','Two reference letters from community leaders','Application form with supporting statement'],
    ],
    'orphan' => [
        'title'       => 'Orphan Scholarship',
        'tagline'     => 'Full support for our most vulnerable students',
        'color'       => 'bg-purple-600',
        'description' => 'JDCA is committed to ensuring that orphaned students do not miss the opportunity to receive higher education. The Orphan Scholarship provides full tuition fee coverage for students who have lost their father or both parents.',
        'eligibility' => ['Loss of father or both parents (documented)','Must be a domicile holder of Gilgit-Baltistan','Enrolled as a full-time student','Annual review required'],
        'benefits'    => ['100% tuition fee waiver for the entire program duration','Annual renewable upon re-verification','College ID issued with scholarship status'],
        'docs'        => ['Death certificate of parent(s)','Domicile certificate','CNIC/Form-B of student','Guardian letter (if applicable)','Application form'],
    ],
    'special' => [
        'title'       => 'Special Category Scholarship',
        'tagline'     => 'Supporting students with exceptional circumstances',
        'color'       => 'bg-amber-600',
        'description' => 'The Special Category Scholarship is for students who face unique challenges that significantly impact their ability to pursue education — including students with disabilities, serious medical conditions, or other special circumstances assessed on an individual basis.',
        'eligibility' => ['Documented disability or special circumstance','Recommendation from a registered medical doctor or social welfare officer','Enrolled as a full-time student','Case reviewed by the scholarship committee'],
        'benefits'    => ['Variable fee waiver based on individual assessment (25%–100%)','Additional support as deemed appropriate by the college','Case-by-case review ensures fair assessment'],
        'docs'        => ['Medical certificate or disability assessment','Supporting letter from relevant authority','Application form with detailed statement','Any additional documents requested by the committee'],
    ],
];
$data = $scholarships[$type] ?? [];
@endphp
@section('title', $data['title'] ?? 'Scholarship')

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('scholarships') }}" class="hover:text-white">Scholarships</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">{{ $data['title'] }}</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $data['title'] }}</h1>
            <p class="text-white/70 text-sm mt-1">{{ $data['tagline'] }}</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12 space-y-8">

    <section>
        <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color:var(--site-brand)">Overview</h2>
        <p class="text-stone-700 leading-relaxed">{{ $data['description'] }}</p>
    </section>

    <div class="grid sm:grid-cols-3 gap-6">
        @foreach([
            ['Eligibility Criteria', $data['eligibility'], 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Benefits', $data['benefits'], 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Required Documents', $data['docs'], 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ] as [$heading, $items, $icon])
        <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden">
            <div class="site-brand-gradient px-4 py-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-white/80 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                <h3 class="text-sm font-bold text-white">{{ $heading }}</h3>
            </div>
            <ul class="divide-y divide-stone-100 p-1">
                @foreach($items as $item)
                <li class="px-4 py-2.5 text-sm text-stone-700 flex items-start gap-2">
                    <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" style="color:var(--site-gold)" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>

    <div class="rounded-2xl p-6 text-center" style="background:var(--site-brand)">
        <h3 class="text-white font-bold mb-2">Ready to Apply?</h3>
        <p class="text-white/80 text-sm mb-4">Submit your scholarship application with your admission form or directly to the administration office.</p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ route('admissions') }}" class="rounded-lg bg-white px-5 py-2.5 text-sm font-semibold transition hover:bg-stone-100" style="color:var(--site-brand)">Apply for Admission</a>
            <a href="{{ route('scholarships') }}" class="rounded-lg border border-white/30 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">View All Scholarships</a>
        </div>
    </div>
</div>
@endsection
