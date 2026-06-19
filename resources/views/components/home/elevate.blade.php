@php
    $elevate = $homeSections['elevate-learning']['content'] ?? [];
    $elevateImage = $elevate['main_image'] ?? 'assets/images/photo-1588072432836-e10032774350.jpg';
    $elevateImageUrl = str_starts_with($elevateImage, 'assets/')
        ? asset($elevateImage)
        : \Illuminate\Support\Facades\Storage::disk('public')->url($elevateImage);
@endphp

<section class="border-y border-stone-200/50 bg-white py-14 md:py-20" aria-labelledby="elevate-heading">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 text-center md:mb-12">
            <h2 id="elevate-heading" class="mb-3 font-display text-2xl font-semibold tracking-tight text-ink sm:text-3xl md:text-[2rem]">
                {{ $elevate['section_title'] ?? 'Elevate your learning' }}
            </h2>
            <div class="mx-auto mb-4 flex justify-center" aria-hidden="true">
                <div class="h-1 w-16 rounded-full bg-gradient-to-r from-brand to-brand-dark sm:w-20"></div>
            </div>
            <p class="mx-auto max-w-2xl text-sm leading-relaxed text-stone-600 md:text-base">
                {{ $elevate['section_text'] ?? 'Discover your potential through programs designed by educators and industry leaders.' }}
            </p>
        </div>
    </div>
    <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 sm:px-6 md:grid-cols-2 md:gap-10">

        <div class="text-left">
            <div class="mb-5 inline-flex items-center gap-2 rounded-md border border-stone-200/80 bg-surface px-4 py-2 text-sm shadow-md shadow-stone-900/10">
                <span class="text-brand" aria-hidden="true">🎓</span>
                <span class="font-medium text-brand">{{ $elevate['badge_text'] ?? 'Premium education' }}</span>
            </div>

            <h3 class="mb-4 max-w-xl font-display text-xl font-semibold leading-snug tracking-tight text-ink sm:text-2xl md:text-[1.75rem]">
                {{ $elevate['heading'] ?? 'World-class programs for every learner' }}
            </h3>

            <p class="mb-6 max-w-prose text-sm leading-relaxed text-stone-600 sm:text-base md:mb-7">
                {{ $elevate['description'] ?? 'Flexible formats, expert faculty, and resources that fit your goals.' }}
            </p>

            <div class="mb-6 flex gap-8 md:mb-7">
                @foreach(($elevate['stats'] ?? []) as $stat)
                    <div>
                        <p class="text-2xl font-bold text-brand sm:text-3xl">{{ $stat['value'] ?? '' }}</p>
                        <p class="text-xs uppercase tracking-wide text-stone-500">{{ $stat['label'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                <a href="{{ !empty($elevate['primary_button_link']) && \Illuminate\Support\Facades\Route::has($elevate['primary_button_link']) ? route($elevate['primary_button_link']) : route('programs') }}"
                    class="inline-flex items-center justify-center rounded-md bg-brand px-7 py-3 text-sm font-semibold text-white transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 sm:text-base">
                    {{ $elevate['primary_button_text'] ?? 'Explore programs' }}
                </a>
                <a href="{{ !empty($elevate['secondary_button_link']) && \Illuminate\Support\Facades\Route::has($elevate['secondary_button_link']) ? route($elevate['secondary_button_link']) . '#contact' : route('contact') . '#contact' }}"
                    class="flex items-center gap-2 text-sm font-medium text-brand transition hover:text-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 sm:text-base">
                    {{ $elevate['secondary_button_text'] ?? 'Contact admissions' }} →
                </a>
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-xl md:max-w-none">
            <div
                class="absolute -top-3 left-1/2 z-20 flex w-[calc(100%-1rem)] max-w-md -translate-x-1/2 flex-wrap justify-center gap-2 sm:-top-5 sm:left-auto sm:right-3 sm:w-auto sm:max-w-none sm:translate-x-0 sm:justify-end sm:gap-2.5 md:-top-6 md:right-4">
                @foreach(($elevate['feature_cards'] ?? []) as $card)
                    <div
                        class="w-[calc(50%-0.25rem)] min-w-[8.5rem] max-w-[10.5rem] rounded-xl border border-white/50 bg-white/90 p-2.5 shadow-lg shadow-stone-900/15 backdrop-blur-md transition duration-300 ease-out hover:scale-[1.02] hover:shadow-xl sm:w-40 sm:max-w-none md:w-44 md:p-3">
                        <div class="flex items-center gap-2">
                            <div class="rounded-lg bg-brand/10 p-1.5 text-xs" aria-hidden="true">{{ $card['icon'] ?? '' }}</div>
                            <h4 class="text-xs font-semibold leading-tight text-neutral-900 md:text-sm">{{ $card['title'] ?? '' }}</h4>
                        </div>
                        <p class="mt-1 text-[10px] leading-snug text-stone-600 md:text-xs">{{ $card['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>

            <img src="{{ $elevateImageUrl }}"
                alt="Students studying together on campus"
                class="relative z-0 w-full rounded-2xl object-cover shadow-xl shadow-stone-900/[0.15]" loading="lazy"
                decoding="async" width="800" height="600">
        </div>

    </div>
</section>
