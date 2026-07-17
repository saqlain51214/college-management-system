@extends('layouts.public')
@section('title', 'Downloads — ' . ($college->college_name ?? 'JDCA'))

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-5xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Downloads</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Downloads</h1>
            <p class="text-white/70 text-sm mt-1">Forms, prospectus, and official documents</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-5xl px-4 sm:px-6 py-12 space-y-10">

@php
$categoryLabels = [
    'admission'      => 'Admission Documents',
    'academic'       => 'Academic Documents',
    'administrative' => 'Administrative Forms',
    'general'        => 'General Documents',
];
@endphp

@if(!empty($downloads) && count($downloads) > 0)
    {{-- Dynamic downloads from admin --}}
    @foreach($downloads as $category => $files)
    <section>
        <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color:var(--site-brand)">
            {{ $categoryLabels[$category] ?? ucfirst($category) }}
        </h2>
        <div class="grid sm:grid-cols-2 gap-3">
            @foreach($files as $file)
            <a href="{{ $file->file_url ?? '#' }}"
               @if($file->file_url ?? false) target="_blank" rel="noopener" @endif
               class="flex items-center gap-4 rounded-xl border border-stone-200 bg-white px-4 py-3.5 hover:shadow-md hover:border-stone-300 transition group">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 text-xs font-bold text-white" style="background:var(--site-brand)">
                    {{ $file->file_type ?? 'PDF' }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-stone-800 text-sm truncate group-hover:text-[var(--site-brand)] transition">{{ $file->title }}</p>
                    @if($file->description)
                    <p class="text-xs text-stone-400 mt-0.5 truncate">{{ $file->description }}</p>
                    @else
                    <p class="text-xs text-stone-400 mt-0.5">{{ $file->file_type ?? 'PDF' }} Document</p>
                    @endif
                </div>
                <svg class="w-4 h-4 shrink-0 text-stone-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </a>
            @endforeach
        </div>
    </section>
    @endforeach
@else
    {{-- Static fallback while no files have been uploaded --}}
    @foreach([
        ['Admission Documents', 'admission', [
            ['Admission Form 2024–25', 'PDF'],
            ['Prospectus 2024–25', 'PDF'],
            ['Admission Policy', 'PDF'],
            ['Fee Structure Chart', 'PDF'],
        ]],
        ['Academic Documents', 'academic', [
            ['Academic Calendar', 'PDF'],
            ['Examination Schedule', 'PDF'],
            ['Semester Rules & Regulations', 'PDF'],
            ['Degree Program Outlines', 'PDF'],
        ]],
        ['Administrative Forms', 'administrative', [
            ['Migration Certificate Application', 'PDF'],
            ['Character Certificate Form', 'PDF'],
            ['Duplicate DMC Request Form', 'PDF'],
            ['Student Withdrawal Form', 'PDF'],
        ]],
    ] as [$sectionTitle, $cat, $files])
    <section>
        <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color:var(--site-brand)">{{ $sectionTitle }}</h2>
        <div class="grid sm:grid-cols-2 gap-3">
            @foreach($files as [$name, $type])
            <div class="flex items-center gap-4 rounded-xl border border-stone-200 bg-white px-4 py-3.5 opacity-60 cursor-not-allowed">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 text-xs font-bold text-white" style="background:var(--site-brand)">
                    {{ $type }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-stone-800 text-sm truncate">{{ $name }}</p>
                    <p class="text-xs text-stone-400 mt-0.5">Coming soon</p>
                </div>
                <svg class="w-4 h-4 shrink-0 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            @endforeach
        </div>
    </section>
    @endforeach
@endif

    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-4 flex items-start gap-4">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-amber-800">
            Documents are updated periodically. If you need a specific document not listed here, please
            <a href="{{ route('contact') }}" class="font-semibold underline">contact the administration office</a>.
        </p>
    </div>
</div>
@endsection
