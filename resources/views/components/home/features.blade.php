@php
    // Live counts from the DB, with graceful fallbacks so a fresh site never shows "0".
    $s = $stats ?? [];
    $stat  = fn($count, $fallback) => ($count ?? 0) > 0 ? number_format($count) . '+' : $fallback;
    $years = ($s['years_of_excellence'] ?? 0) > 0 ? $s['years_of_excellence'] . '+' : '10+';

    $items = [
        [$stat($s['students'] ?? 0, '500+'), 'Enrolled Students',   'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        [$stat($s['teachers'] ?? 0, '25+'),  'Qualified Faculty',   'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
        [$stat($s['departments'] ?? 0, '7'), 'Departments',         'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        [$years,                             'Years of Excellence', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

<section class="relative overflow-hidden py-14 sm:py-20 site-brand-gradient">
    {{-- decorative accents --}}
    <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full opacity-10" style="background:var(--site-gold)"></div>
    <div class="pointer-events-none absolute -left-20 bottom-0 h-72 w-72 rounded-full opacity-[0.07] bg-white"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 text-center">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.22em]" style="color:var(--site-gold)">By the numbers</p>
            <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">A Growing Community of Learners</h2>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach ($items as [$v, $l, $path])
                <div class="group rounded-2xl bg-white/[0.06] p-6 text-center ring-1 ring-white/10 backdrop-blur-sm transition duration-300 hover:bg-white/10 hover:-translate-y-1">
                    <span class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl text-white shadow-lg transition group-hover:scale-110 site-gold-gradient">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                        </svg>
                    </span>
                    <div class="font-display text-3xl font-extrabold text-white sm:text-4xl">{{ $v }}</div>
                    <div class="mt-1.5 text-[11px] font-bold uppercase tracking-[0.14em] text-white/60">{{ $l }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
