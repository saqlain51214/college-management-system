@php
    $s    = $pageContent['programs'] ?? [];
    $feat = $programs->first();
@endphp

<section class="py-10 sm:py-12" style="background:var(--site-surface)" aria-labelledby="programs-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-7 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between" data-reveal>
            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em]" style="color:var(--site-gold)">Academic Programmes</p>
                <h2 id="programs-heading" class="font-display text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">
                    {{ $s['section_title'] ?? 'Programmes Offered' }}
                </h2>
            </div>
            <a href="{{ route('programs') }}"
               class="shrink-0 self-start sm:self-auto text-sm font-semibold underline underline-offset-4 transition hover:opacity-70"
               style="color:var(--site-brand)">View all →</a>
        </div>

        {{-- Main grid --}}
        <div class="grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @if($feat)
            {{-- Featured card --}}
            <a href="{{ route('programs') }}"
               class="group relative flex flex-col overflow-hidden rounded-2xl sm:col-span-2 lg:col-span-1"
               style="min-height:220px">
                <img src="{{ asset('assets/images/photo-1519389950473-47ba0277781c.jpg') }}"
                     alt="{{ $feat->name }}"
                     class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                     loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-stone-950/92 via-stone-950/40 to-stone-950/10"></div>
                <span class="absolute top-3 left-3 sm:top-4 sm:left-4 rounded-md px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white z-10"
                      style="background:var(--site-gold)">Featured</span>
                <div class="relative mt-auto p-4 sm:p-6 text-white z-10">
                    <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-white/50">{{ $feat->short_name ?? 'Programme' }}</p>
                    <h3 class="mb-1.5 font-display text-lg sm:text-xl font-bold leading-snug">{{ $feat->name }}</h3>
                    <p class="mb-3 text-xs sm:text-sm text-white/60 line-clamp-2">
                        {{ \Illuminate\Support\Str::limit($feat->description ?? 'A rigorous academic programme designed to build strong foundations.', 100) }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="rounded-full border border-white/25 px-2.5 py-0.5 text-[11px] text-white/70">{{ $feat->duration_years ?? 4 }} years</span>
                        <span class="text-xs sm:text-sm font-semibold text-white/70 group-hover:text-white transition">Details →</span>
                    </div>
                </div>
            </a>
            @endif

            {{-- Other programmes --}}
            @foreach($programs->skip(1)->take(4) as $p)
            <div class="group flex flex-col rounded-2xl border border-stone-200 bg-white p-5 sm:p-6 shadow-sm transition hover:border-stone-300 hover:shadow-md">
                <div class="mb-4 flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-xl" style="background:var(--site-brand)">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <p class="mb-1 text-[10px] sm:text-[11px] font-bold uppercase tracking-wide" style="color:var(--site-gold)">{{ $p->short_name ?? 'Programme' }}</p>
                <h3 class="mb-2 font-display text-base sm:text-lg font-bold text-stone-900">{{ $p->name }}</h3>
                <p class="mb-4 flex-1 text-xs sm:text-sm leading-relaxed text-stone-500">
                    {{ \Illuminate\Support\Str::limit($p->description ?? 'Designed to prepare graduates for professional and academic pursuits.', 90) }}
                </p>
                <div class="flex items-center justify-between border-t border-stone-100 pt-3 sm:pt-4 text-xs text-stone-400">
                    <span>{{ $p->duration_years ?? 4 }} years</span>
                    <a href="{{ route('programs') }}" class="font-semibold transition" style="color:var(--site-brand)">Learn more →</a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Admission CTA --}}
        <div class="mt-8 sm:mt-12 overflow-hidden rounded-2xl shadow-lg">
            <div class="grid sm:grid-cols-2">
                <div class="px-6 py-8 sm:px-10 sm:py-10" style="background:var(--site-brand)">
                    <p class="mb-2 text-[10px] sm:text-xs font-bold uppercase tracking-[0.14em] text-white/50">Admissions Open</p>
                    <h3 class="mb-2 sm:mb-3 font-display text-xl sm:text-2xl lg:text-3xl font-bold text-white leading-snug">
                        Begin Your Academic Journey at JDCA
                    </h3>
                    <p class="text-xs sm:text-sm text-white/60">Applications for the new session are now being accepted.</p>
                </div>
                <div class="flex flex-col items-start justify-center gap-3 px-6 py-8 sm:px-10 sm:py-10" style="background:var(--site-brand-dark,#3d1520)">
                    <a href="{{ route('admissions') }}"
                       class="inline-flex items-center gap-2 rounded-lg px-6 py-2.5 sm:px-7 sm:py-3 text-sm font-bold text-white transition hover:opacity-90"
                       style="background:var(--site-gold)">
                        Apply Now
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('admissions.fee-structure') }}" class="text-xs sm:text-sm text-white/50 underline underline-offset-4 hover:text-white/80 transition">View fee structure</a>
                </div>
            </div>
        </div>

    </div>
</section>
