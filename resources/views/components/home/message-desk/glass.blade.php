{{-- Glass Halo — frosted-glass card over a soft gradient, circular photo
     with a warm gold glow. The most contemporary of the six templates. --}}
<section class="relative overflow-hidden py-14 sm:py-20"
         style="background:linear-gradient(160deg, color-mix(in srgb, var(--site-body-bg) 90%, black 3%), var(--site-body-bg) 45%, color-mix(in srgb, var(--site-gold) 6%, var(--site-body-bg)))">
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 text-center sm:mb-14" data-reveal>
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em]"
                  style="background:color-mix(in srgb, var(--site-gold) 15%, transparent); color:var(--site-gold)">Leadership</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-stone-900 sm:text-4xl">Leadership Message Desk</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-stone-500">A warm welcome from the leadership guiding our institution.</p>
        </div>

        <div class="grid gap-7 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($leaders as $L)
                <a href="{{ route('leadership.message', $L) }}" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}"
                   class="group rounded-3xl border border-white/70 bg-white/55 p-8 text-center shadow-[0_12px_34px_-18px_rgba(15,27,46,0.16)] backdrop-blur-md transition-transform duration-300 hover:-translate-y-1.5">
                    <div class="relative mx-auto mb-4 h-36 w-36">
                        <div class="absolute -inset-3.5 rounded-full opacity-60 blur-md" style="background:radial-gradient(circle, color-mix(in srgb, var(--site-gold) 55%, transparent), transparent 70%)"></div>
                        <div class="relative h-full w-full overflow-hidden rounded-full border-[3px] border-white shadow-[0_6px_20px_-6px_rgba(15,27,46,0.3)]">
                            @if($L->photo_url)
                                <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center site-brand-gradient">
                                    <span class="font-display text-3xl font-bold text-white/90">{{ $initialsFor($L->name) ?: 'JD' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <h3 class="font-display text-lg font-bold text-stone-900">{{ $L->name }}</h3>
                    <p class="text-[12.5px] font-bold" style="color:var(--site-gold)">{{ $L->designation }}</p>
                    @if($L->organization)
                        <p class="mt-0.5 text-[11.5px] text-stone-400">{{ $L->organization }}</p>
                    @endif
                    <p class="mt-3.5 text-[13.5px] leading-relaxed text-stone-600 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($L->message), 170) }}</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 text-[12.5px] font-bold" style="color:var(--site-brand)">
                        Read Full Message
                        <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
