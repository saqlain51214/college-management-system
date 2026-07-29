{{-- Minimal Editorial — no card box, generous whitespace, a numbered index
     (these are genuinely sequential leadership positions), circular photo,
     hairline rule. The quietest, most premium-feeling option. --}}
<section class="relative overflow-hidden py-16 sm:py-24" style="background:var(--site-body-bg)">
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-12 text-center sm:mb-16" data-reveal>
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em]"
                  style="background:color-mix(in srgb, var(--site-gold) 15%, transparent); color:var(--site-gold)">Leadership</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-stone-900 sm:text-4xl">Leadership Message Desk</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-stone-500">A warm welcome from the leadership guiding our institution.</p>
        </div>

        <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($leaders as $L)
                <a href="{{ route('leadership.message', $L) }}" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}" class="group block text-center">
                    <div class="font-display text-sm" style="color:var(--site-gold)">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="mx-auto mt-2.5 h-28 w-28 overflow-hidden rounded-full">
                        @if($L->photo_url)
                            <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center site-brand-gradient">
                                <span class="font-display text-2xl font-bold text-white/90">{{ $initialsFor($L->name) ?: 'JD' }}</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="mt-4 font-display text-lg font-bold text-stone-900">{{ $L->name }}</h3>
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-stone-400">{{ $L->designation }}</p>
                    <span class="mx-auto my-4 block h-px w-6" style="background:var(--site-gold)"></span>
                    <p class="text-[13.5px] leading-relaxed text-stone-600 line-clamp-4">{{ \Illuminate\Support\Str::limit(strip_tags($L->message), 190) }}</p>
                    <span class="mt-3.5 inline-block text-xs font-bold uppercase tracking-[0.06em]" style="color:var(--site-brand)">Read Full Message →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
