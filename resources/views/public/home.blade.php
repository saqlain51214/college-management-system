@extends('layouts.public')
@section('title', 'Home — ' . ($college->college_name ?? 'JDCA'))

@section('content')

{{-- ────────────────────────────────────────────────────────────
     HERO
     ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden" style="min-height: 72vh;">

    {{-- Gradient background --}}
    <div class="absolute inset-0" style="background: linear-gradient(160deg,#3d0d0d 0%,#1e293b 55%,#0f172a 100%);"></div>
    {{-- Dot-grid overlay --}}
    <div class="absolute inset-0 pointer-events-none" style="opacity:.045; background-image:radial-gradient(circle,#fff 1px,transparent 1px); background-size:32px 32px;"></div>

    {{-- Content --}}
    <div class="relative z-10 mx-auto max-w-6xl px-4 sm:px-6 flex flex-col items-center justify-center text-center text-white py-20 sm:py-24 md:py-28"
         style="min-height: 72vh;">

        {{-- Badge --}}
        <div class="h-anim-1 mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-medium sm:text-sm">
            <span class="h-2 w-2 rounded-full shrink-0 animate-pulse" style="background:var(--c-gold);"></span>
            Excellence in Education Since {{ $college->established_year ?? 2010 }}
        </div>

        {{-- Heading --}}
        <h1 class="h-anim-2 mb-5 max-w-3xl font-display font-semibold leading-tight tracking-tight text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem]">
            Shaping Minds for<br>
            <span style="color:var(--c-gold);">Tomorrow's World</span>
        </h1>

        {{-- Sub-text --}}
        <p class="h-anim-3 mb-8 max-w-xl text-sm leading-relaxed sm:text-base md:max-w-2xl" style="color:rgba(255,255,255,.75);">
            {{ $college->college_name ?? 'Jinnah School &amp; Degree College Astore' }} —
            Empowering students with knowledge, character, and skills in the heart of Gilgit-Baltistan.
        </p>

        {{-- CTA Buttons --}}
        <div class="h-anim-4 mb-8 flex flex-col gap-3 w-full max-w-xs sm:flex-row sm:w-auto sm:max-w-none sm:gap-4">
            <a href="{{ route('admissions') }}"
               class="inline-flex items-center justify-center gap-2 rounded-md px-7 py-3 text-sm font-semibold text-white shadow-lg transition hover:opacity-90"
               style="background:var(--c-primary);">
                Apply online
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center justify-center gap-2 rounded-md border-2 border-white/50 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                Schedule Tour
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Notice badge --}}
        <div class="h-anim-5 max-w-lg rounded-md border border-white/20 bg-white/10 px-4 py-2 text-xs backdrop-blur-sm sm:text-sm" style="color:rgba(255,255,255,.80);">
            @if($notices->count())
                📢 {{ Str::limit($notices->first()->title, 80) }}
            @else
                Admissions {{ date('Y') }} Open — Join JDCA and shape your future!
            @endif
        </div>

    </div>

    {{-- Stats bar --}}
    <div class="relative border-t" style="border-color:rgba(255,255,255,.10); background:rgba(0,0,0,.35);">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="grid grid-cols-2 sm:grid-cols-4">
                @foreach([
                    [$stats['students'],            'Students'],
                    [$stats['teachers'],            'Faculty'],
                    [$stats['programs'],            'Programs'],
                    [$stats['years_of_excellence'], 'Years of Excellence'],
                ] as $i => [$num, $label])
                <div class="py-5 text-center {{ $i > 0 ? 'border-l' : '' }}" style="border-color:rgba(255,255,255,.10);">
                    <div class="font-display text-2xl font-bold sm:text-3xl" style="color:var(--c-gold);"
                         data-counter="{{ $num }}" data-suffix="+">{{ number_format($num) }}+</div>
                    <div class="mt-1 text-[11px] font-medium sm:text-xs" style="color:rgba(255,255,255,.55);">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</section>


{{-- ────────────────────────────────────────────────────────────
     HIGHLIGHT CARDS  (overlaps hero)
     ──────────────────────────────────────────────────────────── --}}
<section class="relative z-10 -mt-8 sm:-mt-10 md:-mt-12 border-b border-stone-200/60 pb-12 md:pb-16" style="background: linear-gradient(to bottom, transparent, var(--c-surface) 4rem);">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">

            @foreach([
                ['M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222','Board-Topping Results','Consistently producing high-achieving students with structured preparation for board exams and university admissions.'],
                ['M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','Qualified Faculty','Experienced educators with advanced degrees dedicated to student success and academic excellence.'],
                ['M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z','Scenic Campus','Located in the breathtaking Astore valley — where the mountains of Gilgit-Baltistan inspire learning.'],
            ] as $i => [$path, $title, $desc])
            <div class="lift-card rounded-xl border border-stone-200/80 bg-white p-6 shadow-md reveal d{{ $i+1 }}">
                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-lg" style="background:rgba(123,26,26,.09);">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="color:var(--c-primary);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                    </svg>
                </div>
                <h3 class="mb-2 font-semibold text-base" style="color:var(--c-ink);">{{ $title }}</h3>
                <p class="text-sm leading-relaxed text-stone-500">{{ $desc }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>


{{-- ────────────────────────────────────────────────────────────
     ANNOUNCEMENT BAR
     ──────────────────────────────────────────────────────────── --}}
<div class="border-b" style="background:rgba(123,26,26,.08); border-color:rgba(123,26,26,.18);">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 py-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <span class="inline-flex shrink-0 items-center rounded px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-white" style="background:var(--c-primary);">New</span>
            <p class="min-w-0 truncate text-sm font-medium" style="color:var(--c-ink);">
                Admissions {{ date('Y') }} are now open — Apply today and secure your seat at JDCA.
            </p>
        </div>
        <a href="{{ route('admissions') }}"
           class="inline-flex h-9 w-full shrink-0 items-center justify-center rounded-md bg-white px-5 text-sm font-semibold shadow-sm transition hover:shadow-md sm:w-auto"
           style="color:var(--c-primary); border:1px solid rgba(123,26,26,.25);">
            Apply Now
        </a>
    </div>
</div>


{{-- ────────────────────────────────────────────────────────────
     ABOUT
     ──────────────────────────────────────────────────────────── --}}
<section class="border-b border-stone-200/60 bg-white py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="grid grid-cols-1 items-center gap-10 md:grid-cols-2 md:gap-14">

            {{-- Text --}}
            <div class="reveal from-left">
                <p class="mb-2 text-xs font-bold uppercase tracking-widest" style="color:var(--c-gold);">About JDCA</p>
                <h2 class="mb-5 font-display text-2xl font-semibold leading-snug tracking-tight sm:text-3xl md:text-[2.1rem]" style="color:var(--c-primary);">
                    Discover the Minds<br>Shaping the Future
                </h2>
                <p class="mb-5 text-sm leading-relaxed text-stone-600 sm:text-base">
                    Jinnah School &amp; Degree College Astore (JDCA) has been a beacon of quality education in
                    Gilgit-Baltistan since {{ $college->established_year ?? 2010 }}. Affiliated with
                    <strong class="font-semibold text-stone-800">{{ $college->affiliation ?? 'Karakoram International University (KIU)' }}</strong>,
                    we offer a nurturing academic environment where students can fulfil their potential.
                </p>
                <p class="mb-8 text-sm leading-relaxed text-stone-600 sm:text-base">
                    Nestled in the scenic Astore valley, our campus blends natural beauty with modern academic
                    facilities. From intermediate to degree-level programs, every course builds knowledge, character, and career readiness.
                </p>

                <div class="mb-8 grid grid-cols-3 gap-4 border-t border-stone-100 pt-6">
                    @foreach([
                        [$stats['students'],  'Students'],
                        [$stats['teachers'],  'Faculty'],
                        [$stats['programs'],  'Programs'],
                    ] as [$n, $l])
                    <div>
                        <p class="font-display text-2xl font-semibold sm:text-3xl" style="color:var(--c-primary);">{{ number_format($n) }}+</p>
                        <p class="mt-0.5 text-xs font-medium text-stone-500">{{ $l }}</p>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('about') }}"
                   class="inline-flex items-center justify-center rounded-md px-6 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
                   style="background:var(--c-primary);">
                    Learn More
                </a>
            </div>

            {{-- College info card --}}
            <div class="reveal from-right">
                <div class="rounded-xl p-7 text-white shadow-xl" style="background:linear-gradient(135deg,var(--c-primary) 0%,#3b0a0a 100%);">
                    <p class="mb-5 text-xs font-bold uppercase tracking-widest" style="color:var(--c-gold);">College at a Glance</p>
                    <div class="space-y-4 mb-7">
                        @foreach([
                            ['📅','Founded',     $college->established_year ?? 2010],
                            ['🏛️','Affiliation', $college->affiliation ?? 'KIU, Gilgit-Baltistan'],
                            ['👤','Principal',   $college->principal_name ?? 'Arif Ali'],
                            ['📍','Location',    $college->address ?? 'Astore, Gilgit-Baltistan'],
                        ] as [$icon,$key,$val])
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-lg flex items-center justify-center shrink-0 text-base" style="background:rgba(255,255,255,.12);">{{ $icon }}</div>
                            <div class="min-w-0">
                                <p class="text-[11px]" style="color:rgba(255,255,255,.5);">{{ $key }}</p>
                                <p class="text-sm font-semibold text-white truncate">{{ $val }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="pt-5" style="border-top:1px solid rgba(255,255,255,.14);">
                        <p class="font-display text-xl font-semibold text-white leading-snug">Excellence in<br>Education</p>
                        <div class="mt-3 h-1 w-14 rounded-full" style="background:var(--c-gold);"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ────────────────────────────────────────────────────────────
     FEATURED PROGRAMS
     ──────────────────────────────────────────────────────────── --}}
<section class="border-b border-stone-200/60 py-14 md:py-20" style="background:var(--c-surface);">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        {{-- Section header --}}
        <div class="mb-10 text-center reveal">
            <h2 class="mb-3 font-display text-2xl font-semibold tracking-tight sm:text-3xl" style="color:var(--c-ink);">Featured Programs</h2>
            <div class="mx-auto mb-3 flex justify-center"><span class="section-bar"></span></div>
            <p class="mx-auto max-w-xl text-sm leading-relaxed text-stone-600 sm:text-base">
                Discover our academic programs designed to build careers and shape futures.
            </p>
        </div>

        @if($programs->count())
        {{-- Stats row --}}
        <div class="mb-10 grid grid-cols-1 gap-6 rounded-xl border border-stone-200/60 bg-white p-6 shadow-sm sm:grid-cols-3">
            <div class="text-center sm:text-left">
                <p class="font-display text-3xl font-semibold" style="color:var(--c-primary);">{{ number_format($stats['students']) }}+</p>
                <p class="text-xs text-stone-500 mt-1">Active Students</p>
            </div>
            <div class="text-center sm:text-left border-t border-stone-100 pt-4 sm:border-t-0 sm:pt-0 sm:border-l sm:pl-6">
                <p class="font-display text-3xl font-semibold" style="color:var(--c-primary);">{{ $stats['programs'] }}+</p>
                <p class="text-xs text-stone-500 mt-1">Programs Offered</p>
            </div>
            <div class="text-center sm:text-left border-t border-stone-100 pt-4 sm:border-t-0 sm:pt-0 sm:border-l sm:pl-6">
                <p class="font-display text-3xl font-semibold" style="color:var(--c-primary);">{{ $stats['teachers'] }}+</p>
                <p class="text-xs text-stone-500 mt-1">Expert Faculty</p>
            </div>
        </div>

        {{-- Program cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($programs->take(6) as $prog)
            <div class="lift-card flex flex-col rounded-xl border border-slate-200/60 bg-white shadow-md overflow-hidden reveal d{{ ($loop->index % 6) + 1 }}"
                 style="border-left:3px solid var(--c-primary);">
                <div class="flex-1 p-5">
                    <p class="mb-1 text-xs font-bold uppercase" style="color:var(--c-primary);">
                        {{ $prog->degree_type?->shortLabel() ?? $prog->degree_type?->value ?? 'Program' }}
                    </p>
                    <h3 class="mb-2 text-base font-semibold leading-snug text-stone-900">{{ $prog->name }}</h3>
                    <div class="mb-3 flex flex-wrap items-center gap-3 text-xs text-stone-400">
                        @if($prog->duration_years)
                        <span>⏱ {{ $prog->duration_years }} {{ Str::plural('Year', $prog->duration_years) }}</span>
                        @endif
                        @if($prog->total_semesters)
                        <span>📚 {{ $prog->total_semesters }} Semesters</span>
                        @endif
                    </div>
                    @if($prog->description)
                    <p class="text-sm leading-relaxed text-stone-500 line-clamp-2">{{ Str::limit($prog->description, 90) }}</p>
                    @endif
                </div>
                <div class="px-5 pb-5">
                    <a href="{{ route('programs') }}" class="text-sm font-semibold transition hover:opacity-75" style="color:var(--c-primary);">
                        Learn More →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-12 text-center text-stone-400 text-sm">No programs available at the moment.</div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('programs') }}"
               class="inline-flex items-center justify-center rounded-md px-7 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 shadow"
               style="background:var(--c-primary);">
                View All Programs
            </a>
        </div>

    </div>
</section>


{{-- ────────────────────────────────────────────────────────────
     CAMPUS LIFE
     ──────────────────────────────────────────────────────────── --}}
<section class="border-b border-stone-200/60 bg-white py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        {{-- Section header --}}
        <div class="mb-10 text-center reveal">
            <h2 class="mb-3 font-display text-2xl font-semibold tracking-tight sm:text-3xl" style="color:var(--c-ink);">Campus Life</h2>
            <div class="mx-auto mb-3 flex justify-center"><span class="section-bar"></span></div>
            <p class="mx-auto max-w-xl text-sm leading-relaxed text-stone-600 sm:text-base">
                Experience learning in the scenic Astore valley — where nature meets education.
            </p>
        </div>

        <div class="grid grid-cols-1 items-start gap-8 md:grid-cols-2 md:gap-12">

            {{-- Left: text + stats --}}
            <div class="reveal from-left">
                <p class="mb-2 text-xs font-bold uppercase tracking-widest text-stone-400">Student Life</p>
                <h3 class="mb-4 font-display text-xl font-semibold leading-snug sm:text-2xl" style="color:var(--c-ink);">
                    Everything you need for a better education, on one campus
                </h3>
                <p class="mb-6 text-sm leading-relaxed text-stone-600 sm:text-base">
                    Surrounded by majestic mountains, our campus provides an inspiring setting for growth.
                    Modern facilities, dedicated faculty, and a vibrant student community make JDCA a true home for every learner.
                </p>
                <div class="mb-6 flex gap-8 border-t border-stone-100 pt-5">
                    <div>
                        <p class="font-display text-3xl font-semibold" style="color:var(--c-primary);">85+</p>
                        <p class="text-xs text-stone-500 mt-1">Student Activities</p>
                    </div>
                    <div>
                        <p class="font-display text-3xl font-semibold" style="color:var(--c-primary);">150+</p>
                        <p class="text-xs text-stone-500 mt-1">Annual Events</p>
                    </div>
                    <div>
                        <p class="font-display text-3xl font-semibold" style="color:var(--c-primary);">10+</p>
                        <p class="text-xs text-stone-500 mt-1">Clubs &amp; Societies</p>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="text-sm font-semibold transition hover:opacity-70" style="color:var(--c-primary);">
                    Explore campus life →
                </a>
            </div>

            {{-- Right: facility cards --}}
            <div class="grid grid-cols-2 gap-4 reveal from-right">
                @foreach([
                    ['🏛️','Library',  'Books, periodicals, and digital resources.'],
                    ['🔬','Labs',     'Science and computer laboratories.'],
                    ['⚽','Sports',   'Multi-sport grounds for fitness and teamwork.'],
                    ['🏠','Hostel',   'Safe accommodation for outstation students.'],
                ] as [$icon,$title,$desc])
                <div class="rounded-xl border border-stone-200/60 bg-stone-50 p-4 shadow-sm">
                    <div class="mb-2 text-2xl">{{ $icon }}</div>
                    <h4 class="mb-1 text-sm font-semibold text-stone-900">{{ $title }}</h4>
                    <p class="text-xs leading-relaxed text-stone-500">{{ $desc }}</p>
                </div>
                @endforeach
            </div>

        </div>

    </div>
</section>


{{-- ────────────────────────────────────────────────────────────
     LATEST NEWS
     ──────────────────────────────────────────────────────────── --}}
<section class="border-b border-stone-200/60 py-14 md:py-20" style="background:var(--c-surface);">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        {{-- Header row --}}
        <div class="mb-10 flex flex-wrap items-end justify-between gap-4 reveal">
            <div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest" style="color:var(--c-gold);">Stay Updated</p>
                <h2 class="font-display text-2xl font-semibold tracking-tight sm:text-3xl" style="color:var(--c-ink);">Latest News</h2>
                <span class="section-bar mt-3 block"></span>
            </div>
            <a href="{{ route('news') }}"
               class="inline-flex items-center rounded-md border px-5 py-2 text-sm font-semibold transition hover:text-white hover:bg-[color:var(--c-primary)]"
               style="border-color:var(--c-primary); color:var(--c-primary);">
                View All
            </a>
        </div>

        @if($news->count())
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($news->take(4) as $i => $article)
            @php
                $catColors = ['news'=>'#1e3a5f','announcement'=>'#7B1A1A','event'=>'#15803d','notice'=>'#92400e'];
                $cat   = strtolower($article->category ?? 'news');
                $color = $catColors[$cat] ?? '#1e3a5f';
            @endphp
            <article class="group lift-card flex flex-col rounded-xl border border-slate-200/60 bg-white shadow-sm reveal d{{ $i+1 }}">
                {{-- Top color accent --}}
                <div class="h-1.5 w-full rounded-t-xl" style="background:{{ $color }};"></div>
                <div class="flex flex-1 flex-col p-5">
                    <p class="mb-2 text-xs" style="color:{{ $color }};">
                        {{ ucfirst($article->category ?? 'News') }}
                        @if($article->published_date)
                        <span class="text-stone-400"> / {{ \Carbon\Carbon::parse($article->published_date)->format('d M Y') }}</span>
                        @endif
                    </p>
                    <h3 class="mb-2 flex-1 text-sm font-semibold leading-snug text-stone-900 line-clamp-2 transition group-hover:text-[color:var(--c-primary)]">
                        {{ $article->title }}
                    </h3>
                    @if($article->excerpt)
                    <p class="mb-4 text-xs leading-relaxed text-stone-500 line-clamp-3">{{ $article->excerpt }}</p>
                    @endif
                    <a href="{{ route('news.show', $article->slug) }}"
                       class="mt-auto text-xs font-semibold transition hover:opacity-70" style="color:var(--c-primary);">
                        Read More →
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="py-14 text-center rounded-xl bg-white border border-stone-200/60 shadow-sm">
            <p class="text-stone-400">No news articles published yet. Check back soon!</p>
        </div>
        @endif

    </div>
</section>


{{-- ────────────────────────────────────────────────────────────
     UPCOMING EVENTS
     ──────────────────────────────────────────────────────────── --}}
<section class="border-b border-stone-200/60 bg-white py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        {{-- Section header --}}
        <div class="mb-10 text-center reveal">
            <p class="mb-1 text-xs font-bold uppercase tracking-widest" style="color:var(--c-gold);">Mark Your Calendar</p>
            <h2 class="mb-3 font-display text-2xl font-semibold tracking-tight sm:text-3xl" style="color:var(--c-ink);">Upcoming Events</h2>
            <div class="mx-auto mb-3 flex justify-center"><span class="section-bar"></span></div>
            <p class="mx-auto max-w-xl text-sm leading-relaxed text-stone-600 sm:text-base">
                Don't miss these exciting events at JDCA — be part of the action!
            </p>
        </div>

        @php
        $events = [
            ['day'=>'15','month'=>'Jul','year'=>'2025','title'=>'Admissions Open Day',  'desc'=>'Meet faculty, explore programs, and get a guided campus tour.'],
            ['day'=>'05','month'=>'Aug','year'=>'2025','title'=>'Annual Sports Day',     'desc'=>'Inter-department competitions and prize distribution. Show your team spirit!'],
            ['day'=>'20','month'=>'Sep','year'=>'2025','title'=>'Convocation Ceremony',  'desc'=>'Graduation ceremony for Class 2024–25. Celebrating academic achievement.'],
        ];
        @endphp

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
            @foreach($events as $i => $event)
            <div class="lift-card flex flex-col overflow-hidden rounded-xl border border-slate-200/60 bg-white shadow-md reveal d{{ $i+1 }}">
                <div class="flex items-center gap-4 border-b border-stone-100 p-5">
                    <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl text-white shadow-sm" style="background:var(--c-primary);">
                        <span class="font-display text-xl font-bold leading-none">{{ $event['day'] }}</span>
                        <span class="text-[10px] uppercase font-semibold opacity-80 mt-0.5">{{ $event['month'] }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-stone-400 mb-0.5">{{ $event['month'] }} {{ $event['day'] }}, {{ $event['year'] }}</p>
                        <h3 class="text-sm font-semibold text-stone-900 leading-snug">{{ $event['title'] }}</h3>
                    </div>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <p class="flex-1 text-sm leading-relaxed text-stone-500 mb-4">{{ $event['desc'] }}</p>
                    <a href="{{ route('admissions') }}"
                       class="block w-full rounded-md py-2.5 text-center text-sm font-semibold text-white transition hover:opacity-90"
                       style="background:var(--c-primary);">
                        Register →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>


{{-- ────────────────────────────────────────────────────────────
     CTA STRIP
     ──────────────────────────────────────────────────────────── --}}
<section class="py-14 md:py-20" style="background:var(--c-surface);">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="rounded-2xl p-8 text-center text-white shadow-xl sm:p-12 reveal"
             style="background:linear-gradient(135deg,var(--c-primary) 0%,#1e293b 100%);">
            <p class="mb-3 text-xs font-bold uppercase tracking-widest" style="color:var(--c-gold);">Join Us</p>
            <h2 class="mb-4 font-display text-2xl font-semibold sm:text-3xl">Ready to Start Your Academic Journey?</h2>
            <p class="mb-8 mx-auto max-w-lg text-sm leading-relaxed sm:text-base" style="color:rgba(255,255,255,.72);">
                Applications are open for {{ date('Y') }}. Take the first step toward a brighter future at Gilgit-Baltistan's premier institution.
            </p>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center sm:gap-4">
                <a href="{{ route('admissions') }}"
                   class="inline-flex items-center justify-center rounded-md px-8 py-3 text-sm font-semibold text-white shadow transition hover:opacity-90"
                   style="background:var(--c-gold);">
                    Apply for Admission
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center rounded-md border-2 border-white/40 px-8 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
