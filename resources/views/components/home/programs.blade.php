@php
    $programSection = $pageContent['programs'] ?? [];
    $featuredProgram = $programs->first();
@endphp

<section class="border-y border-stone-200/50 bg-surface py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        <div class="mb-10 text-center md:mb-12">
            <h2 class="mb-3 font-display text-2xl font-semibold tracking-tight text-ink sm:text-3xl md:text-[2rem]">{{ $programSection['section_title'] ?? 'Featured Programs' }}</h2>
            <div class="mx-auto mb-4 flex justify-center" aria-hidden="true">
                <div class="h-1 w-16 rounded-full bg-gradient-to-r from-brand to-brand-dark sm:w-20"></div>
            </div>
            <p class="mx-auto max-w-2xl text-sm leading-relaxed text-stone-600 md:text-base">
                {{ $programSection['section_text'] ?? 'Discover our comprehensive range of academic programs designed to prepare you for success.' }}
            </p>
        </div>

        <div class="mb-8 grid items-center gap-8 md:mb-10 md:grid-cols-2 md:gap-10">

            <!-- LEFT TEXT -->
            <div class="text-left">
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-stone-500 sm:text-sm">{{ $programSection['intro_label'] ?? 'Programs' }}</p>

                <h3 class="mb-4 font-display text-2xl font-semibold leading-snug text-ink sm:text-3xl md:mb-6 md:text-[2rem]">
                    {{ $programSection['intro_title'] ?? 'Discover Excellence in Education' }}
                </h3>

                <p class="mb-6 max-w-prose text-sm leading-relaxed text-stone-600 md:mb-7 md:text-base">
                    {{ $programSection['intro_text'] ?? 'Explore intermediate and degree programmes designed to build strong academic foundations and career-ready skills.' }}
                </p>

                <div class="flex flex-col gap-5 text-brand sm:flex-row sm:gap-8 md:gap-10">
                    @foreach(($programSection['stats'] ?? []) as $stat)
                        <div class="text-center sm:text-left">
                            <p class="text-lg font-semibold sm:text-2xl">{{ $stat['value'] ?? '' }}</p>
                            <p class="text-xs text-stone-500 sm:text-sm">{{ $stat['label'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <img src="{{ asset('assets/images/photo-1576495199011-eb94736d05d6.jpg') }}" alt="Students in lecture hall"
                    class="h-[220px] w-full rounded-xl object-cover object-top shadow-lg shadow-stone-900/[0.1] sm:h-[300px]"
                    loading="lazy" decoding="async" />
            </div>

        </div>

        <div class="grid gap-5 md:grid-cols-2 md:gap-6">

            <div
                class="overflow-hidden rounded-xl border border-slate-200/60 bg-white/95 shadow-md shadow-stone-900/[0.06] backdrop-blur-sm transition duration-300 ease-out hover:-translate-y-1 hover:shadow-xl hover:shadow-stone-900/12">

                <div class="relative">
                    <img src="{{ asset('assets/images/photo-1519389950473-47ba0277781c.jpg') }}"
                        alt="Students working together on laptops in a classroom"
                        class="w-full h-40 sm:h-48 object-cover object-top" loading="lazy" decoding="async" />

                    <span
                        class="absolute top-3 sm:top-4 left-3 sm:left-4 bg-white text-xs px-3 py-1 rounded-full shadow">
                        ⭐ Top Rated
                    </span>
                </div>

                <div class="p-4 sm:p-6">
                    <p class="text-xs text-brand uppercase mb-2 font-medium">{{ $featuredProgram->short_name ?? 'Featured' }}</p>
                    <h4 class="mb-2 text-base font-semibold text-neutral-900 sm:text-lg">{{ $featuredProgram->name ?? 'Computer Science & AI' }}</h4>

                    <p class="mb-4 text-xs text-stone-500 sm:text-sm">
                        {{ \Illuminate\Support\Str::limit($featuredProgram->description ?? 'Explore our flagship academic offering designed for strong foundations and career pathways.', 90) }}
                    </p>

                    <div class="flex justify-between text-xs text-stone-500 mb-4">
                        <span>⏱ {{ $featuredProgram->duration_years ?? 4 }} Years</span>
                        <span>🎓 {{ $featuredProgram->degree_level ?? 'Program' }}</span>
                    </div>

                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <a href="{{ route('programs') }}" class="text-brand font-medium">Learn More</a>
                        <span class="text-stone-400">👥 Featured</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT LIST -->
            <div class="space-y-4 md:space-y-6">
                @foreach($programs->skip(1)->take(4) as $program)
                    <div
                        class="flex items-start gap-3 rounded-xl border border-slate-100/90 bg-white/95 p-3 shadow-md shadow-stone-900/[0.05] backdrop-blur-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-lg sm:items-center sm:gap-4 sm:p-4">
                        <img src="{{ asset('assets/images/photo-1551836022-d5d88e9218df.jpg') }}"
                            alt="{{ $program->name }}"
                            class="w-12 sm:w-16 h-12 sm:h-16 rounded object-cover object-top flex-shrink-0" />

                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-brand uppercase font-medium">{{ $program->short_name ?? 'Program' }}</p>
                            <h4 class="font-semibold text-neutral-900 text-sm sm:text-base">{{ $program->name }}</h4>
                            <p class="text-xs sm:text-sm text-stone-500 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($program->description ?? 'Explore this academic program.', 90) }}
                            </p>
                            <div class="text-xs text-stone-400 mt-1">{{ $program->duration_years ?? 4 }} Years</div>
                        </div>

                        <div class="text-stone-400 text-lg flex-shrink-0">→</div>
                    </div>
                @endforeach

            </div>

        </div>

    </div>
</section>
