@php
    $s = $pageContent['programs'] ?? [];
@endphp

<section class="py-10 sm:py-12" style="background:var(--site-surface)" aria-labelledby="programs-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between" data-reveal>
            <div class="max-w-xl">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em]" style="color:var(--site-gold)">Academic Programmes</p>
                <h2 id="programs-heading" class="font-display text-2xl font-bold text-stone-900 sm:text-3xl">
                    {{ $s['section_title'] ?? 'Programmes Offered' }}
                </h2>
                <p class="mt-2 text-sm text-stone-500">{{ $s['section_text'] ?? 'Discover our comprehensive range of academic programs designed to prepare you for success.' }}</p>
            </div>

            <a href="{{ route('programs') }}"
               class="inline-flex shrink-0 items-center gap-2 self-start text-sm font-bold hover:underline sm:self-auto"
               style="color:var(--site-brand)">
                View all
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @php $featuredHero = ($programs ?? collect())->first(); $featuredRest = ($programs ?? collect())->skip(1)->take(3); @endphp

        @if($featuredHero)
        <div class="mt-8 grid gap-4 sm:gap-6 lg:grid-cols-5">

            {{-- Hero program --}}
            <a href="{{ route('programs') }}#{{ $featuredHero->slug }}"
               class="group relative flex flex-col overflow-hidden rounded-2xl bg-stone-900 lg:col-span-3" data-reveal>
                <div class="relative h-56 sm:h-72 lg:h-80 overflow-hidden">
                    @if($featuredHero->banner_image_url)
                        <img src="{{ $featuredHero->banner_image_url }}" alt="{{ $featuredHero->name }}"
                             class="h-full w-full object-cover opacity-80 transition duration-500 group-hover:scale-105 group-hover:opacity-90"
                             loading="lazy" decoding="async">
                    @else
                        <div class="h-full w-full site-brand-gradient"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                </div>
                <div class="absolute inset-x-0 bottom-0 flex flex-col p-5 sm:p-7">
                    <div class="mb-2 sm:mb-3 flex flex-wrap items-center gap-2">
                        @if($featuredHero->is_featured)
                            <span class="rounded-full px-2.5 py-0.5 font-bold uppercase tracking-wide text-white text-[10px]" style="background:var(--site-gold)">Featured</span>
                        @endif
                        @if($featuredHero->short_name || $featuredHero->degree_type)
                            <span class="rounded-full px-2.5 py-0.5 font-semibold uppercase tracking-wide text-white text-[10px]" style="background:rgba(255,255,255,0.2)">{{ $featuredHero->short_name ?: $featuredHero->degree_type->shortLabel() }}</span>
                        @endif
                    </div>
                    <h3 class="mb-1.5 sm:mb-2 font-display text-lg sm:text-xl lg:text-2xl font-bold leading-snug text-white group-hover:text-white/90">
                        {{ $featuredHero->name }}
                    </h3>
                    <p class="text-xs sm:text-sm leading-relaxed text-white/70 line-clamp-2">
                        {{ Str::limit($featuredHero->description ?? 'Structured preparation for board and university exams.', 130) }}
                    </p>
                    <span class="mt-3 sm:mt-4 inline-flex items-center gap-1 text-xs font-semibold text-white/90 transition group-hover:gap-2">
                        Details
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </a>

            {{-- Remaining programs --}}
            <div class="flex flex-col gap-3 sm:gap-4 lg:col-span-2">
                @forelse($featuredRest as $program)
                <a href="{{ route('programs') }}#{{ $program->slug }}"
                   class="group flex gap-3 sm:gap-4 rounded-xl p-3 sm:p-4 ring-1 ring-stone-200/70 transition hover:ring-stone-300" data-reveal
                   style="background:var(--site-surface)">
                    <div class="h-16 w-20 sm:h-20 sm:w-24 shrink-0 overflow-hidden rounded-lg">
                        @if($program->banner_image_url)
                            <img src="{{ $program->banner_image_url }}" alt="{{ $program->name }}"
                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                 loading="lazy" decoding="async">
                        @else
                            <div class="h-full w-full site-brand-gradient flex items-center justify-center">
                                <svg class="w-6 h-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.42A12.02 12.02 0 0121 15.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-1.5a12.02 12.02 0 012.84-4.92L12 14z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="mb-0.5 flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wide text-stone-400">
                            @if($program->is_featured)
                                <span class="font-bold" style="color:var(--site-gold)">Featured</span> ·
                            @endif
                            {{ $program->short_name ?: ($program->degree_type?->shortLabel() ?? 'Programme') }}
                        </p>
                        <h3 class="font-display text-sm font-bold leading-snug text-stone-900 transition group-hover:text-[var(--site-brand)] line-clamp-2">
                            {{ $program->name }}
                        </h3>
                        <p class="mt-0.5 text-xs leading-relaxed text-stone-500 line-clamp-1 sm:line-clamp-2">
                            {{ Str::limit($program->description ?? 'Structured preparation for board and university exams.', 80) }}
                        </p>
                    </div>
                </a>
                @empty
                <p class="text-sm text-stone-400 py-4">No additional programmes.</p>
                @endforelse
            </div>

        </div>
        @endif

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
