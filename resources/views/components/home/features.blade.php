<div style="background:var(--site-brand)">
    <div class="mx-auto max-w-7xl">
        <dl class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-white/10
                   [&>*:nth-child(n+3)]:border-t [&>*:nth-child(n+3)]:border-white/10
                   lg:[&>*:nth-child(n+3)]:border-t-0">
            @foreach([
                ['500+','Enrolled Students',   'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['25+', 'Qualified Faculty',   'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                ['7',   'Departments',         'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['10+', 'Years of Excellence', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as [$v,$l,$path])
            <div class="flex flex-col items-center justify-center gap-1.5 py-6 px-3 sm:py-9 sm:px-4 text-center text-white">
                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white/35" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $path }}"/>
                </svg>
                <dt class="font-display text-2xl font-bold sm:text-3xl lg:text-4xl">{{ $v }}</dt>
                <dd class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.12em] sm:tracking-[0.14em] text-white/50 leading-tight">{{ $l }}</dd>
            </div>
            @endforeach
        </dl>
    </div>
</div>
