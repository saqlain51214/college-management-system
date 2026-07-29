{{-- Modern Leadership Cards — equal-height white cards, large photo block,
     navy/gold accent. Text always sits below the photo, never overlaid on
     it, so nothing can ever become unreadable regardless of photo. --}}
<section class="relative overflow-hidden py-14 sm:py-20" style="background:var(--site-body-bg)">
    <div class="pointer-events-none absolute right-[-10%] top-[-10%] h-96 w-96 rounded-full opacity-10 blur-3xl" style="background:var(--site-brand)"></div>
    <div class="pointer-events-none absolute left-[-10%] bottom-[-10%] h-96 w-96 rounded-full opacity-10 blur-3xl" style="background:var(--site-gold)"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 text-center sm:mb-14" data-reveal>
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em]"
                  style="background:color-mix(in srgb, var(--site-gold) 15%, transparent); color:var(--site-gold)">Leadership</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-stone-900 sm:text-4xl">Leadership Message Desk</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-stone-500">A warm welcome from the leadership guiding our institution.</p>
        </div>

        <div class="grid items-stretch gap-7 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($leaders as $L)
                <a href="{{ route('leadership.message', $L) }}" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}"
                   class="group flex h-full flex-col overflow-hidden rounded-[24px] bg-white shadow-[0_10px_40px_-18px_rgba(0,0,0,0.16)] ring-1 ring-stone-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.26)]">
                    <div class="relative aspect-[4/5] w-full overflow-hidden">
                        @if($L->photo_url)
                            <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy"
                                 class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center site-brand-gradient">
                                <span class="font-display text-4xl font-bold text-white/90">{{ $initialsFor($L->name) ?: 'JD' }}</span>
                            </div>
                        @endif
                        <span class="absolute bottom-0 left-0 h-1 w-full" style="background:linear-gradient(90deg,var(--site-brand),var(--site-gold))"></span>
                    </div>

                    <div class="flex flex-1 flex-col p-6 text-center">
                        <h3 class="font-display text-lg font-bold text-stone-900">{{ $L->name }}</h3>
                        <p class="text-sm font-semibold" style="color:var(--site-gold)">{{ $L->designation }}</p>
                        @if($L->organization)
                            <p class="text-xs text-stone-400">{{ $L->organization }}</p>
                        @endif

                        <p class="mt-4 flex-1 text-sm leading-relaxed text-stone-600 line-clamp-4">{{ \Illuminate\Support\Str::limit(strip_tags($L->message), 170) }}</p>

                        <span class="mx-auto mt-5 inline-flex items-center gap-1.5 rounded-full border border-[var(--site-brand)] bg-transparent px-5 py-2 text-sm font-semibold text-[var(--site-brand)] transition-all duration-300 group-hover:gap-2.5 group-hover:bg-[var(--site-brand)] group-hover:text-white">
                            Read Full Message
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
