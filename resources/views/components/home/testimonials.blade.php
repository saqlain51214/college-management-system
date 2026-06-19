@php
    $testimonialsSection = $homeSections['testimonials']['content'] ?? [];
    $testimonials = $testimonialsSection['items'] ?? [];
    $trackWidthClass = match (count($testimonials)) {
        1 => 'w-[100%]',
        2 => 'w-[200%]',
        3 => 'w-[300%]',
        default => 'w-[400%]',
    };
@endphp

<section id="testimonials" class="relative overflow-hidden border-y border-stone-200/60 bg-surface py-14 md:py-20"
    aria-labelledby="testimonials-heading">
    <div class="pointer-events-none absolute -right-24 -top-20 h-80 w-80 rounded-full bg-brand/12 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -bottom-16 -left-24 h-72 w-72 rounded-full bg-stone-300/25 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 text-center md:mb-12">
            <h2 id="testimonials-heading" class="mb-3 font-display text-2xl font-semibold tracking-tight text-ink sm:text-3xl md:text-[2rem]">
                {{ $testimonialsSection['section_title'] ?? 'Testimonials' }}
            </h2>
            <div class="mx-auto mb-4 flex justify-center" aria-hidden="true">
                <div class="h-1 w-16 rounded-full bg-gradient-to-r from-brand to-brand-dark sm:w-20"></div>
            </div>
            <p class="mx-auto max-w-2xl text-sm leading-relaxed text-stone-600 md:text-base">
                {{ $testimonialsSection['section_text'] ?? 'Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit.' }}
            </p>
        </div>

        <!-- Simple slider: one card, transform track (no horizontal scroll) -->
        <div class="mx-auto max-w-4xl">
            <div class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white shadow-lg shadow-stone-900/10">
                <div id="testimonialTrack" class="flex {{ $trackWidthClass }} transition-transform duration-500 ease-out will-change-transform">
                    @foreach($testimonials as $item)
                        @php
                            $portraitUrl = str_starts_with($item['portrait'] ?? '', 'assets/')
                                ? asset($item['portrait'])
                                : \Illuminate\Support\Facades\Storage::disk('public')->url($item['portrait'] ?? '');
                            $avatarUrl = str_starts_with($item['avatar'] ?? '', 'assets/')
                                ? asset($item['avatar'])
                                : \Illuminate\Support\Facades\Storage::disk('public')->url($item['avatar'] ?? '');
                        @endphp
                        <article class="box-border {{ count($testimonials) === 4 ? 'w-1/4' : (count($testimonials) === 3 ? 'w-1/3' : (count($testimonials) === 2 ? 'w-1/2' : 'w-full')) }} shrink-0 px-6 py-8 sm:px-10 sm:py-10">
                            <div class="flex flex-col gap-8 md:flex-row md:items-stretch md:gap-10">
                                <div class="mx-auto w-full max-w-[240px] shrink-0 md:mx-0 md:max-w-[260px]">
                                    <img src="{{ $portraitUrl }}"
                                        alt="{{ $item['portrait_alt'] ?? 'Testimonial portrait' }}"
                                        class="h-[280px] w-full rounded-xl object-cover object-center shadow-md sm:h-[300px] md:h-full md:min-h-[280px]"
                                        loading="lazy" decoding="async" width="520" height="650" />
                                </div>
                                <div class="flex min-w-0 flex-1 flex-col justify-center text-left">
                                    <p class="mb-6 font-display text-lg font-semibold leading-relaxed text-brand sm:text-xl md:text-2xl">
                                        &ldquo;{{ $item['quote'] ?? '' }}&rdquo;
                                    </p>
                                    <div class="flex items-center gap-3 border-t border-stone-100 pt-5">
                                        <img src="{{ $avatarUrl }}" alt=""
                                            class="h-12 w-12 shrink-0 rounded-full object-cover ring-2 ring-brand/25" width="48" height="48" loading="lazy" decoding="async" />
                                        <div>
                                            <p class="font-semibold text-ink">{{ $item['name'] ?? '' }}</p>
                                            <p class="text-sm text-stone-600">{{ $item['role'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex flex-col items-center gap-4 sm:flex-row sm:justify-center sm:gap-6">
                <div class="flex items-center gap-2">
                    <button type="button" id="testimonialPrev"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-300 bg-white text-ink shadow-sm transition hover:border-brand hover:text-brand focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                        aria-label="Previous testimonial">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button type="button" id="testimonialNext"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-brand bg-brand text-white shadow-sm transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2"
                        aria-label="Next testimonial">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-center gap-2" id="testimonialDots" role="tablist" aria-label="Testimonial slides"></div>
            </div>
        </div>
    </div>
</section>
