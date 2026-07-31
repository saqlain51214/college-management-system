@php
    $s = $pageContent['programs'] ?? [];
@endphp

{{--
    Deliberately a teaser, not a card grid — the full listing already lives at
    /programs, which is now reachable from the main nav (Academics dropdown).
    Repeating those same program cards here duplicated that page; this section
    now just points visitors to it.
--}}
<section class="py-10 sm:py-12" style="background:var(--site-surface)" aria-labelledby="programs-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        <div class="flex flex-col gap-6 rounded-2xl border border-stone-200 bg-white p-6 sm:flex-row sm:items-center sm:justify-between sm:p-10" data-reveal>
            <div class="max-w-xl">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em]" style="color:var(--site-gold)">Academic Programmes</p>
                <h2 id="programs-heading" class="font-display text-2xl font-bold text-stone-900 sm:text-3xl">
                    {{ $s['section_title'] ?? 'Programmes Offered' }}
                </h2>
                <p class="mt-2 text-sm text-stone-500">{{ $s['section_text'] ?? 'Discover our comprehensive range of academic programs designed to prepare you for success.' }}</p>

                @if(!empty($s['stats']))
                <div class="mt-5 flex flex-wrap gap-6">
                    @foreach($s['stats'] as $stat)
                    <div>
                        <p class="font-display text-xl font-bold text-stone-900">{{ $stat['value'] ?? '' }}</p>
                        <p class="text-xs text-stone-500">{{ $stat['label'] ?? '' }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <a href="{{ route('programs') }}"
               class="inline-flex shrink-0 items-center gap-2 self-start rounded-lg px-6 py-3 text-sm font-bold text-white transition hover:opacity-90 sm:self-auto"
               style="background:var(--site-brand)">
                Explore all Academic Programmes
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
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
