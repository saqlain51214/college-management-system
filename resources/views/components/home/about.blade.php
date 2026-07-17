@php
    $s   = $pageContent['about'] ?? [];
    $img = str_starts_with($s['image'] ?? 'assets/images/photo-1541339907198-e08756dedf3f.jpg','assets/')
        ? asset($s['image'] ?? 'assets/images/photo-1541339907198-e08756dedf3f.jpg')
        : \Illuminate\Support\Facades\Storage::url($s['image'] ?? '');
@endphp

<section class="overflow-hidden bg-stone-950 py-0" aria-labelledby="about-heading">
    <div class="grid lg:grid-cols-2 min-h-[520px]">

        {{-- Image half --}}
        <div class="relative min-h-[280px] sm:min-h-[360px] lg:min-h-0">
            <img src="{{$img}}" alt="JDCA Campus"
                 class="absolute inset-0 h-full w-full object-cover opacity-70"
                 loading="lazy" decoding="async">
            {{-- Overlay card bottom --}}
            <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-stone-950/90 to-transparent">
                <p class="font-display text-4xl font-bold text-white">{{$s['badge_title'] ?? '10+'}}</p>
                <p class="mt-1 text-sm text-white/60">{{$s['badge_text'] ?? 'Years shaping futures in Astore, Gilgit-Baltistan'}}</p>
            </div>
        </div>

        {{-- Text half --}}
        <div class="flex flex-col justify-center px-8 py-16 sm:px-12 lg:px-16 xl:px-20">
            <p class="mb-4 text-xs font-bold uppercase tracking-[0.18em]" style="color:var(--site-gold)">About JDCA</p>
            <h2 id="about-heading"
                class="mb-6 font-display text-3xl font-bold leading-tight text-white sm:text-4xl xl:text-[2.6rem]"
                style="text-wrap:balance">
                {{$s['title'] ?? 'Educating Minds, Shaping Futures in Gilgit-Baltistan'}}
            </h2>
            <p class="mb-8 text-base leading-relaxed text-white/55 sm:text-[1.05rem]">
                {{$s['description'] ?? 'Jinnah School & Degree College Astore has served the Karakoram region for over a decade — providing quality intermediate and degree education to students from Astore and surrounding valleys, affiliated with Karakoram International University.'}}
            </p>

            @if(!empty($s['stats']))
            <div class="mb-9 grid grid-cols-3 gap-3 border-y border-white/10 py-6 sm:gap-6 sm:py-7">
                @foreach($s['stats'] as $stat)
                <div>
                    <p class="font-display text-2xl font-bold sm:text-3xl" style="color:var(--site-gold)">{{$stat['value']}}</p>
                    <p class="mt-1 text-xs text-white/40">{{$stat['label']}}</p>
                </div>
                @endforeach
            </div>
            @endif

            <a href="{{route('about')}}"
               class="inline-flex w-fit items-center gap-2 rounded-lg px-7 py-3 text-sm font-semibold text-white transition hover:opacity-90"
               style="background:var(--site-brand)">
                {{$s['button_text'] ?? 'Discover Our Story'}}
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
