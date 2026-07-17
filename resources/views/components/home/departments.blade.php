@php
    $depts = \App\Models\Department::visible()->ordered()->get();
    $deptIcons = [
        'computer'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
        'education'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>',
        'physical'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
        'english'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
        'sociology'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
        'continuing'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
        'default'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
    ];
    $getIcon = function(string $slug) use ($deptIcons): string {
        if (str_contains($slug,'computer'))   return $deptIcons['computer'];
        if (str_contains($slug,'education'))  return $deptIcons['education'];
        if (str_contains($slug,'physical') || str_contains($slug,'health')) return $deptIcons['physical'];
        if (str_contains($slug,'english'))    return $deptIcons['english'];
        if (str_contains($slug,'sociology'))  return $deptIcons['sociology'];
        if (str_contains($slug,'continu'))    return $deptIcons['continuing'];
        return $deptIcons['default'];
    };
    $bgColors = [
        'var(--site-brand)',
        'var(--site-gold)',
        '#1e3a5f',
        '#2d5a27',
        '#5a2d7a',
        '#3d4a1e',
    ];
@endphp

@if($depts->isNotEmpty())
<section class="py-16 sm:py-20" style="background:#0f0a0b" aria-labelledby="depts-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.18em]" style="color:var(--site-gold)">Academic Departments</p>
                <h2 id="depts-heading" class="font-display text-3xl font-bold text-white sm:text-4xl">
                    Explore Our Departments
                </h2>
            </div>
            <a href="{{ route('departments') }}"
               class="shrink-0 text-sm font-semibold text-white/50 transition hover:text-white/80">
                All departments →
            </a>
        </div>

        {{-- Department cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($depts->take(6) as $i => $dept)
            <a href="{{ route('departments.show', $dept->slug) }}"
               class="group relative flex flex-col overflow-hidden rounded-2xl border border-white/[0.07] bg-white/[0.04] p-6 transition hover:bg-white/[0.08] hover:border-white/[0.14]">

                {{-- Icon --}}
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl"
                     style="background: {{ $bgColors[$i % count($bgColors)] }}">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $getIcon($dept->slug) !!}
                    </svg>
                </div>

                {{-- Name --}}
                <h3 class="mb-2 font-display text-base font-bold leading-snug text-white group-hover:text-white/90">
                    {{ $dept->name }}
                </h3>

                {{-- HOD --}}
                @if($dept->hod_name)
                <p class="mb-3 text-[11px] font-medium text-white/38">
                    HOD: {{ $dept->hod_name }}
                </p>
                @endif

                {{-- Short description --}}
                <p class="mb-5 flex-1 text-xs leading-relaxed text-white/40 line-clamp-2">
                    {{ \Illuminate\Support\Str::limit($dept->description ?? 'Quality education in ' . $dept->name . '.', 90) }}
                </p>

                {{-- Arrow --}}
                <div class="flex items-center gap-1.5 text-[11px] font-semibold"
                     style="color:var(--site-gold)">
                    View Department
                    <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif
