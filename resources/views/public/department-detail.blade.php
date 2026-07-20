@extends('layouts.public')
@section('title', $department->name)

@section('content')

{{-- Page Banner --}}
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('departments') }}" class="hover:text-white">Departments</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">{{ $department->name }}</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $department->name }}</h1>
        </div>
    </div>
</div>

<div class="mx-auto max-w-6xl px-4 sm:px-6 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Left Sidebar --}}
        <aside class="lg:w-72 shrink-0 space-y-6">

            {{-- Head of Department --}}
            <div class="rounded-2xl overflow-hidden border border-stone-200 bg-white shadow-sm">
                <div class="site-brand-gradient py-3 px-4">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Head of Department</h2>
                </div>
                <div class="p-5 flex flex-col items-center text-center">
                    @if($department->hod_photo_url)
                        <img src="{{ $department->hod_photo_url }}" alt="{{ $department->hod_name }}"
                             class="w-28 h-28 rounded-full object-cover border-4 border-stone-100 shadow mb-3">
                    @else
                        <div class="w-28 h-28 rounded-full flex items-center justify-center text-3xl font-black text-white mb-3 shadow"
                             style="background: var(--site-brand)">
                            {{ strtoupper(substr($department->hod_name ?? 'H', 0, 1)) }}
                        </div>
                    @endif
                    <h3 class="font-bold text-stone-800 text-base leading-snug">{{ $department->hod_name ?? 'To Be Announced' }}</h3>
                    @if($department->hod_designation)
                        <p class="text-xs text-stone-500 mt-0.5">{{ $department->hod_designation }}</p>
                    @endif
                    <div class="mt-4 w-full space-y-2 text-left text-sm">
                        @if($department->email)
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $department->email }}" class="text-stone-600 hover:site-brand-text break-all">{{ $department->email }}</a>
                        </div>
                        @endif
                        @if($department->phone)
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ $department->phone }}" class="text-stone-600 hover:site-brand-text">{{ $department->phone }}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Programs Offered --}}
            @if($programs->isNotEmpty())
            <div class="rounded-2xl overflow-hidden border border-stone-200 bg-white shadow-sm">
                <div class="site-brand-gradient py-3 px-4">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Programs Offered</h2>
                </div>
                <ul class="p-4 space-y-2">
                    @foreach($programs as $prog)
                    <li class="flex items-start gap-2 text-sm text-stone-700">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" style="color:var(--site-gold)" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $prog->name }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

        </aside>

        {{-- Main Content --}}
        <main class="flex-1 min-w-0 space-y-8">

            {{-- Department Introduction --}}
            @if($department->description)
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color: var(--site-brand)">Department Introduction</h2>
                <div class="prose prose-stone max-w-none text-stone-700 leading-relaxed">
                    {!! nl2br(e($department->description)) !!}
                </div>
            </section>
            @endif

            {{-- Vision & Mission --}}
            @if($department->vision || $department->mission)
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color: var(--site-brand)">Program Vision &amp; Mission</h2>
                <div class="grid sm:grid-cols-2 gap-6">
                    @if($department->vision)
                    <div class="rounded-xl p-5 bg-blue-50 border border-blue-100">
                        <h3 class="font-bold text-stone-800 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" style="color:var(--site-brand)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Vision
                        </h3>
                        <div class="text-sm text-stone-700 leading-relaxed prose prose-sm max-w-none">{!! $department->vision !!}</div>
                    </div>
                    @endif
                    @if($department->mission)
                    <div class="rounded-xl p-5 bg-amber-50 border border-amber-100">
                        <h3 class="font-bold text-stone-800 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" style="color:var(--site-gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Mission
                        </h3>
                        <div class="text-sm text-stone-700 leading-relaxed prose prose-sm max-w-none">{!! $department->mission !!}</div>
                    </div>
                    @endif
                </div>
            </section>
            @endif

            {{-- Message from HOD --}}
            @if($department->hod_message)
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-4 border-b-2" style="border-color: var(--site-brand)">Message from Head of Department</h2>
                <div class="rounded-xl p-6 bg-stone-50 border border-stone-200 relative">
                    <svg class="absolute top-4 left-4 w-8 h-8 opacity-10" style="color:var(--site-brand)" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <div class="text-stone-700 leading-relaxed italic pl-4 prose prose-sm max-w-none">{!! $department->hod_message !!}</div>
                    <p class="mt-3 font-semibold text-stone-800 pl-4">— {{ $department->hod_name }}, {{ $department->hod_designation }}</p>
                </div>
            </section>
            @endif

            {{-- Faculty Members --}}
            @if($teachers->isNotEmpty())
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-6 border-b-2" style="border-color: var(--site-brand)">Faculty Members</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($teachers as $teacher)
                    <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden text-center p-5 hover:shadow-md transition-shadow">
                        @if($teacher->photo_url ?? null)
                            <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}"
                                 class="w-20 h-20 rounded-full object-cover mx-auto border-4 border-stone-100 mb-3 shadow">
                        @else
                            <div class="w-20 h-20 rounded-full flex items-center justify-center text-xl font-black text-white mx-auto mb-3 shadow"
                                 style="background: var(--site-brand)">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                        @endif
                        <h3 class="font-bold text-stone-800 text-sm leading-snug">{{ $teacher->name }}</h3>
                        @if($teacher->designation)
                            <p class="text-xs text-stone-500 mt-0.5">{{ $teacher->designation }}</p>
                        @endif
                        <div class="mt-3 space-y-1 text-xs text-stone-500">
                            @if($teacher->email)
                            <div class="flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:{{ $teacher->email }}" class="hover:site-brand-text truncate max-w-[150px]">{{ $teacher->email }}</a>
                            </div>
                            @endif
                            @if($teacher->phone)
                            <div class="flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a href="tel:{{ $teacher->phone }}">{{ $teacher->phone }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Course Outlines (semester-wise) --}}
            @if(isset($outlines) && $outlines->isNotEmpty())
            <section>
                <h2 class="text-xl font-bold text-stone-800 pb-2 mb-6 border-b-2" style="border-color: var(--site-brand)">Course Outlines</h2>
                @foreach($outlines as $sem => $items)
                    <p class="mb-2 mt-4 text-xs font-bold uppercase tracking-[0.14em]" style="color:var(--site-gold)">{{ $sem ? 'Semester ' . $sem : 'General' }}</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach($items as $o)
                            <a href="{{ $o->url ?: '#' }}" @if($o->url) target="_blank" @endif
                               class="group flex items-center gap-3 rounded-xl border border-stone-100 bg-stone-50/60 px-4 py-3 transition hover:bg-white hover:shadow-sm">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white" style="background:var(--site-brand)">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V4a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/></svg>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-semibold text-stone-800 truncate">{{ $o->title }}</span>
                                    @if($o->academicProgram)<span class="block text-xs text-stone-400 truncate">{{ $o->academicProgram->name }}</span>@endif
                                </span>
                                <span class="shrink-0 text-xs font-semibold" style="color:var(--site-gold)">{{ $o->file_path ? 'PDF ↓' : 'Open ↗' }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </section>
            @endif

            {{-- Empty state --}}
            @if(!$department->description && !$department->vision && !$department->mission && $teachers->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-300 p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-stone-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <p class="text-stone-500 font-medium">Department information is being updated.</p>
                <p class="text-stone-400 text-sm mt-1">Please check back soon.</p>
            </div>
            @endif

        </main>
    </div>
</div>

@endsection
