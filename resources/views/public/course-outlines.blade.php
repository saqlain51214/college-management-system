@extends('layouts.public')
@section('title', 'Course Outlines — ' . ($college->college_name ?? 'JDCA'))

@section('content')
<div style="padding-top: var(--site-header-offset, 6rem);">

    {{-- Hero header --}}
    <div class="site-brand-gradient py-10 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Course Outlines</span>
            </p>
            <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold text-white">Course Outlines</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/70">Department- and semester-wise course outlines. Click any item to view or download the PDF.</p>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 sm:px-6 py-12">
        @forelse($departments as $dept)
            <div class="mb-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-100">
                {{-- Department header --}}
                <div class="flex items-center gap-3 px-5 sm:px-6 py-4 site-brand-gradient">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg text-white site-gold-gradient">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/></svg>
                    </span>
                    <h2 class="font-display text-lg font-bold text-white">{{ $dept->name }}</h2>
                    <span class="ml-auto text-xs text-white/60">{{ $dept->courseOutlines->count() }} outline(s)</span>
                </div>

                {{-- Outlines grouped by semester --}}
                <div class="divide-y divide-stone-100">
                    @foreach($dept->courseOutlines->groupBy('semester_number') as $sem => $outlines)
                        <div class="px-5 sm:px-6 py-4">
                            <p class="mb-3 text-xs font-bold uppercase tracking-[0.14em]" style="color:var(--site-gold)">
                                {{ $sem ? 'Semester ' . $sem : 'General' }}
                            </p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach($outlines as $o)
                                    <a href="{{ $o->url ?: '#' }}" @if($o->url) target="_blank" @endif
                                       class="group flex items-center gap-3 rounded-xl border border-stone-100 bg-stone-50/60 px-4 py-3 transition hover:border-stone-200 hover:bg-white hover:shadow-sm">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white" style="background:var(--site-brand)">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/></svg>
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-sm font-semibold text-stone-800 truncate">{{ $o->title }}</span>
                                            @if($o->academicProgram)
                                                <span class="block text-xs text-stone-400 truncate">{{ $o->academicProgram->name }}</span>
                                            @elseif($o->description)
                                                <span class="block text-xs text-stone-400 truncate">{{ $o->description }}</span>
                                            @endif
                                        </span>
                                        <span class="shrink-0 text-xs font-semibold" style="color:var(--site-gold)">
                                            {{ $o->file_path ? 'PDF ↓' : 'Open ↗' }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-stone-200 bg-white px-6 py-16 text-center">
                <p class="text-stone-500">Course outlines will be published here soon.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
