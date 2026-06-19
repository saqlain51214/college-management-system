@php
    $campusLife = $homeSections['campus-life']['content'] ?? [];
    $heroImage = $campusLife['hero_image'] ?? 'assets/images/photo-1588072432836-e10032774350.jpg';
    $heroImageUrl = str_starts_with($heroImage, 'assets/')
        ? asset($heroImage)
        : \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage);
@endphp

<section class="border-b border-stone-200/50 bg-white py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        <div class="mb-10 text-center md:mb-12">
            <h2 class="mb-3 font-display text-2xl font-semibold tracking-tight text-ink sm:text-3xl md:text-[2rem]">{{ $campusLife['section_title'] ?? 'Campus life' }}</h2>
            <div class="mx-auto mb-4 flex justify-center" aria-hidden="true">
                <div class="h-1 w-16 rounded-full bg-gradient-to-r from-brand to-brand-dark sm:w-20"></div>
            </div>
            <p class="mx-auto max-w-2xl text-sm leading-relaxed text-stone-600 md:text-base">
                {{ $campusLife['section_text'] ?? 'Experience the vibrant community and opportunities on campus.' }}
            </p>
        </div>

        <div class="mb-10 grid items-center gap-8 md:mb-12 md:grid-cols-2 md:gap-10">

            <!-- LEFT CONTENT -->
            <div class="text-left">
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-stone-500 sm:mb-4 sm:text-sm">{{ $campusLife['intro_label'] ?? 'Student Life' }}</p>

                <h3 class="mb-4 font-display text-2xl font-semibold leading-snug text-ink sm:text-3xl md:mb-5 md:text-[2rem]">
                    {{ $campusLife['heading'] ?? 'Everything you need for a better education, on one campus' }}
                </h3>

                <p class="mb-6 max-w-prose text-sm leading-relaxed text-stone-600 md:mb-7 md:text-base">
                    {{ $campusLife['description'] ?? 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.' }}
                </p>

                <div class="mb-6 flex flex-col gap-6 sm:flex-row sm:gap-10 md:mb-7 md:gap-12">
                    @foreach(($campusLife['stats'] ?? []) as $stat)
                        <div class="text-center sm:text-left">
                            <p class="text-2xl sm:text-3xl font-semibold text-brand">{{ $stat['value'] ?? '' }}</p>
                            <p class="text-xs sm:text-sm text-stone-500 mt-1">{{ $stat['label'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>

                <a href="{{ !empty($campusLife['link_route']) && \Illuminate\Support\Facades\Route::has($campusLife['link_route']) ? route($campusLife['link_route']) : route('gallery') }}"
                    class="inline-block text-sm font-medium text-brand transition hover:text-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                    {{ $campusLife['link_text'] ?? 'Explore student life' }} →
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4">

                <div class="relative col-span-2">
                    <img src="{{ $heroImageUrl }}"
                        alt="{{ $campusLife['hero_image_alt'] ?? 'Campus courtyard with students' }}"
                        class="h-56 w-full rounded-xl object-cover object-top shadow-lg shadow-stone-900/[0.1] sm:h-64"
                        loading="lazy" decoding="async" />

                    <div
                        class="absolute right-3 top-3 flex items-center gap-2 rounded-full border border-white/30 bg-white/80 px-3 py-1.5 text-xs text-stone-800 shadow-md shadow-stone-900/10 backdrop-blur-md sm:right-4 sm:top-4 sm:text-sm">
                        {{ $campusLife['hero_badge'] ?? 'Campus community' }}
                    </div>
                </div>

                @foreach(($campusLife['support_images'] ?? []) as $supportImage)
                    @php
                        $supportUrl = str_starts_with($supportImage['image'] ?? '', 'assets/')
                            ? asset($supportImage['image'])
                            : \Illuminate\Support\Facades\Storage::disk('public')->url($supportImage['image'] ?? '');
                    @endphp
                    <img src="{{ $supportUrl }}" alt="{{ $supportImage['alt'] ?? '' }}"
                        class="h-28 w-full rounded-xl object-cover object-top shadow-md shadow-stone-900/[0.08] sm:h-32"
                        loading="lazy" decoding="async" />
                @endforeach

            </div>

        </div>

        <div class="grid gap-6 md:grid-cols-3 md:gap-6">

            @foreach(($campusLife['cards'] ?? []) as $card)
                @php
                    $cardUrl = str_starts_with($card['image'] ?? '', 'assets/')
                        ? asset($card['image'])
                        : \Illuminate\Support\Facades\Storage::disk('public')->url($card['image'] ?? '');
                @endphp
                <div class="rounded-xl border border-slate-200/60 bg-white/95 p-4 shadow-md shadow-stone-900/[0.06] backdrop-blur-sm">
                    <img src="{{ $cardUrl }}" alt="{{ $card['title'] ?? '' }}"
                        class="mb-3 h-40 w-full rounded-lg object-cover object-top sm:mb-4 sm:h-44"
                        loading="lazy" decoding="async" />
                    <h4 class="mb-2 text-left text-lg font-semibold text-neutral-900">
                        {{ $card['title'] ?? '' }}
                    </h4>
                    <p class="text-left text-sm leading-relaxed text-stone-500">
                        {{ $card['description'] ?? '' }}
                    </p>
                </div>
            @endforeach

        </div>

    </div>
</section>
