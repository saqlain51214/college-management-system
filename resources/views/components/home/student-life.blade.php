@php
    $campusLife    = $homeSections['campus-life']['content'] ?? [];
    $heroImage     = $campusLife['hero_image'] ?? 'assets/images/photo-1588072432836-e10032774350.jpg';
    $heroImageUrl  = str_starts_with($heroImage, 'assets/')
        ? asset($heroImage)
        : \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage);
@endphp

<section class="relative overflow-hidden bg-stone-900 py-20 sm:py-28" aria-labelledby="campus-heading">

    {{-- Faint background texture --}}
    <div class="absolute inset-0 opacity-20">
        <img src="{{ $heroImageUrl }}" alt="" aria-hidden="true"
             class="h-full w-full object-cover object-center" loading="lazy">
        <div class="absolute inset-0 bg-stone-900/80"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-14 max-w-2xl">
            <p class="mb-3 text-xs font-bold uppercase tracking-[0.16em] text-white/40">Life at JDCA</p>
            <h2 id="campus-heading" class="font-display text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-[2.75rem]" style="text-wrap: balance">
                {{ $campusLife['heading'] ?? 'A Campus Built for Growth, Community, and Ambition' }}
            </h2>
        </div>

        <div class="grid gap-8 lg:grid-cols-2 lg:gap-14">

            {{-- Left: description + stats + link --}}
            <div class="flex flex-col justify-center">
                <p class="mb-8 text-base leading-relaxed text-white/65 sm:text-lg">
                    {{ $campusLife['description'] ?? 'Jinnah College offers a complete campus experience — from academic societies and sports competitions to community projects and cultural events — all within a safe, supportive environment in the heart of Astore.' }}
                </p>

                @php $stats = $campusLife['stats'] ?? []; @endphp
                @if($stats)
                <div class="mb-9 grid grid-cols-3 gap-6 border-t border-white/10 pt-8">
                    @foreach($stats as $stat)
                    <div>
                        <p class="font-display text-2xl font-bold text-white sm:text-3xl">{{ $stat['value'] ?? '' }}</p>
                        <p class="mt-1 text-xs font-medium text-white/45">{{ $stat['label'] ?? '' }}</p>
                    </div>
                    @endforeach
                </div>
                @endif

                <a href="{{ route('gallery') }}"
                   class="inline-flex w-fit items-center gap-2 rounded-lg border border-white/20 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                    {{ $campusLife['link_text'] ?? 'Explore student life' }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Right: image mosaic --}}
            <div class="grid grid-cols-2 grid-rows-2 gap-3 sm:gap-4" style="height:360px">
                <div class="row-span-2 overflow-hidden rounded-2xl">
                    <img src="{{ $heroImageUrl }}" alt="Campus life"
                         class="h-full w-full object-cover transition duration-500 hover:scale-105"
                         loading="lazy" decoding="async">
                </div>
                @foreach(($campusLife['support_images'] ?? []) as $i => $img)
                @if($i < 2)
                @php
                    $imgUrl = str_starts_with($img['image'] ?? '', 'assets/')
                        ? asset($img['image'])
                        : \Illuminate\Support\Facades\Storage::disk('public')->url($img['image'] ?? '');
                @endphp
                <div class="overflow-hidden rounded-2xl">
                    <img src="{{ $imgUrl }}" alt="{{ $img['alt'] ?? 'Campus' }}"
                         class="h-full w-full object-cover transition duration-500 hover:scale-105"
                         loading="lazy" decoding="async">
                </div>
                @endif
                @endforeach
                @if(count($campusLife['support_images'] ?? []) < 2)
                    @for($i = count($campusLife['support_images'] ?? []); $i < 2; $i++)
                    <div class="overflow-hidden rounded-2xl bg-white/5"></div>
                    @endfor
                @endif
            </div>

        </div>

        {{-- Bottom: activity cards --}}
        @if(!empty($campusLife['cards']))
        <div class="mt-14 grid gap-5 sm:grid-cols-3">
            @foreach($campusLife['cards'] as $card)
            @php
                $cardUrl = str_starts_with($card['image'] ?? '', 'assets/')
                    ? asset($card['image'])
                    : \Illuminate\Support\Facades\Storage::disk('public')->url($card['image'] ?? '');
            @endphp
            <div class="overflow-hidden rounded-2xl bg-white/8 ring-1 ring-white/10 backdrop-blur-sm transition hover:bg-white/12">
                <div class="h-36 overflow-hidden">
                    <img src="{{ $cardUrl }}" alt="{{ $card['title'] ?? '' }}"
                         class="h-full w-full object-cover transition duration-500 hover:scale-105"
                         loading="lazy" decoding="async">
                </div>
                <div class="p-5">
                    <h4 class="mb-1.5 font-display text-base font-bold text-white">{{ $card['title'] ?? '' }}</h4>
                    <p class="text-sm leading-relaxed text-white/55">{{ $card['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
