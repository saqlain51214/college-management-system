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

        @if(($programs ?? collect())->isNotEmpty())
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($programs->take(4) as $i => $program)
            @if($i === 0)
                {{-- Featured card with image overlay --}}
                <a href="{{ route('programs') }}#{{ $program->slug }}"
                   class="group relative rounded-lg overflow-hidden shadow-lg transition hover:shadow-xl hover:-translate-y-1 sm:col-span-1 lg:row-span-2 lg:col-span-1" data-reveal style="aspect-ratio: auto; min-height: 280px;">
                    @if($program->banner_image_url)
                        <img src="{{ $program->banner_image_url }}" alt="{{ $program->name }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="absolute inset-0 site-brand-gradient"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    <div class="relative h-full flex flex-col justify-between p-5">
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white w-fit" style="background:var(--site-gold)">Featured</span>
                        <div>
                            @if($program->short_name || $program->degree_type)
                                <span class="inline-block rounded px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-white mb-2" style="background:rgba(255,255,255,0.25)">{{ $program->short_name ?: $program->degree_type->shortLabel() }}</span>
                            @endif
                            <h3 class="text-base font-bold text-white leading-snug">{{ $program->name }}</h3>
                            <p class="mt-2 text-xs text-white/85 line-clamp-2">{{ $program->description ?? 'Structured preparation for board and university exams.' }}</p>
                            @if($program->duration_years)
                                <p class="mt-2 text-xs text-white/70">{{ $program->duration_years }} {{ Str::plural('Year', $program->duration_years) }}</p>
                            @endif
                            <span class="mt-3 inline-flex items-center text-xs font-semibold text-white">
                                Details
                                <svg class="h-3 w-3 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @else
                {{-- Standard cards --}}
                <a href="{{ route('programs') }}#{{ $program->slug }}"
                   class="group rounded-lg border border-stone-200/80 bg-white shadow-md overflow-hidden transition hover:shadow-lg hover:-translate-y-0.5" data-reveal>
                    <div class="h-32 site-brand-gradient flex items-center justify-center">
                        <svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.42A12.02 12.02 0 0121 15.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-1.5a12.02 12.02 0 012.84-4.92L12 14z"/></svg>
                    </div>
                    <div class="p-5">
                        @if($program->short_name || $program->degree_type)
                            <span class="inline-block rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide" style="background:var(--site-brand)/10;color:var(--site-brand)">{{ $program->short_name ?: $program->degree_type->shortLabel() }}</span>
                        @endif
                        <h3 class="mt-2 text-sm font-bold text-stone-900">{{ $program->name }}</h3>
                        <p class="mt-2 text-xs text-stone-600 line-clamp-2">{{ $program->description ?? 'Structured preparation for board and university exams.' }}</p>
                        @if($program->duration_years)
                            <p class="mt-3 text-xs text-stone-500">{{ $program->duration_years }} {{ Str::plural('Year', $program->duration_years) }}</p>
                        @endif
                        <span class="mt-3 inline-flex items-center text-xs font-semibold" style="color:var(--site-brand)">
                            Learn more
                            <svg class="h-3.5 w-3.5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @endif
            @endforeach
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
