@php
    $aboutSection = $pageContent['about'] ?? [];
    $aboutImage = $aboutSection['image'] ?? 'assets/images/photo-1541339907198-e08756dedf3f.jpg';
    $aboutImageUrl = str_starts_with($aboutImage, 'assets/')
        ? asset($aboutImage)
        : \Illuminate\Support\Facades\Storage::url($aboutImage);
@endphp

<section class="border-y border-stone-200/70 bg-white py-14 md:py-20">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 md:grid-cols-2 md:gap-14">

        <!-- About Content -->
        <div class="text-left">
            <h2 class="mb-5 max-w-xl font-display text-2xl font-semibold leading-snug text-brand sm:text-3xl md:text-[2.125rem]">
                {{ $aboutSection['title'] ?? 'Discover the Minds Shaping Future' }}
            </h2>

            <p class="mb-6 max-w-prose text-sm leading-relaxed text-stone-600 sm:text-base md:mb-7 md:text-[1.05rem]">
                {{ $aboutSection['description'] ?? 'For over three decades, MySchool has been committed to providing exceptional education that prepares students for success in an ever-changing world. Our holistic approach combines rigorous academics with character development.' }}
            </p>

            <div class="mb-8 grid grid-cols-3 gap-4 md:mb-10">
                @foreach(($aboutSection['stats'] ?? []) as $stat)
                    <div>
                        <p class="font-display text-2xl font-semibold text-brand sm:text-3xl">{{ $stat['value'] ?? '' }}</p>
                        <p class="mt-1 text-xs font-medium text-stone-500 sm:text-sm">{{ $stat['label'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>

            <a href="{{ !empty($aboutSection['button_link']) && \Illuminate\Support\Facades\Route::has($aboutSection['button_link']) ? route($aboutSection['button_link']) . '#about' : route('about') . '#about' }}"
                class="inline-flex items-center justify-center rounded-md bg-brand px-7 py-3 text-sm font-semibold text-white transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 sm:text-base">
                {{ $aboutSection['button_text'] ?? 'Learn More' }}
            </a>
        </div>

        <!-- About Image with overlapping card (bottom-left) -->
        <div class="group relative order-first md:order-last">
            <img src="{{ $aboutImageUrl }}" alt="Campus courtyard"
                class="relative z-0 w-full rounded-xl object-cover shadow-lg shadow-stone-900/15 transition duration-300 group-hover:shadow-xl md:min-h-[320px]"
                loading="lazy" decoding="async" width="900" height="700" />

            <div
                class="absolute bottom-4 left-4 z-10 max-w-[12rem] rounded-lg bg-white p-4 shadow-xl shadow-stone-900/15 ring-1 ring-stone-200/80 sm:bottom-5 sm:left-5 sm:max-w-none sm:p-5">
                <p class="font-display text-xl font-semibold text-brand sm:text-2xl">{{ $aboutSection['badge_title'] ?? 'Excellence' }}</p>
                <p class="mt-1 text-xs text-stone-600 sm:text-sm">{{ $aboutSection['badge_text'] ?? 'A campus built for curiosity, collaboration, and growth.' }}</p>
            </div>
        </div>
    </div>
</section>
