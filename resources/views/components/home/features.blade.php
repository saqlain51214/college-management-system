@php
    $features = $pageContent['features'] ?? [];
@endphp

<section class="relative z-10 -mt-8 bg-gradient-to-b from-transparent via-surface/80 to-surface pb-12 sm:-mt-10 sm:pb-14 md:-mt-12 md:pb-16" aria-label="Highlights">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="grid gap-5 sm:grid-cols-2 sm:gap-6 md:grid-cols-3 md:gap-6">
            <div
                class="rounded-xl border border-stone-200/80 bg-white p-6 shadow-lg shadow-stone-900/10 transition duration-300 ease-out hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10 sm:p-7">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-brand/10 text-brand" aria-hidden="true">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                </div>
                <h3 class="mb-2 text-left font-sans text-base font-semibold text-ink sm:text-lg">{{ $features[0]['title'] ?? 'Board & entry focus' }}</h3>
                <p class="text-left text-sm leading-relaxed text-stone-600">{{ $features[0]['description'] ?? 'Structured preparation for BISE papers, practicals, and university entry tests (MDCAT, ECAT, and others) with regular assessments and counselling.' }}</p>
            </div>

            <div
                class="rounded-xl border border-stone-200/80 bg-white p-6 shadow-lg shadow-stone-900/10 transition duration-300 ease-out hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10 sm:p-7">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-brand/10 text-brand" aria-hidden="true">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="mb-2 text-left font-sans text-base font-semibold text-ink sm:text-lg">{{ $features[1]['title'] ?? 'Qualified faculty' }}</h3>
                <p class="text-left text-sm leading-relaxed text-stone-600">{{ $features[1]['description'] ?? 'Subject teachers with relevant degrees, remedial support before board exams, and parent-teacher meetings aligned with the college calendar.' }}</p>
            </div>

            <div
                class="rounded-xl border border-stone-200/80 bg-white p-6 shadow-lg shadow-stone-900/10 transition duration-300 ease-out hover:-translate-y-1 hover:shadow-xl hover:shadow-brand/10 sm:col-span-2 sm:p-7 md:col-span-1 md:p-7">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-brand/10 text-brand" aria-hidden="true">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="mb-2 text-left font-sans text-base font-semibold text-ink sm:text-lg">{{ $features[2]['title'] ?? 'Campus life' }}</h3>
                <p class="text-left text-sm leading-relaxed text-stone-600">{{ $features[2]['description'] ?? 'Societies, sports, and student welfare within college rules-building confidence for universities across Pakistan and abroad.' }}</p>
            </div>
        </div>
    </div>
</section>
