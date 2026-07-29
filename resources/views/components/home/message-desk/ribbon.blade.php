{{-- Split Ribbon — compact horizontal rows, navy photo panel with a gold
     edge, content beside it. Scans fast for a long leadership list. --}}
<section class="relative overflow-hidden py-14 sm:py-20" style="background:var(--site-body-bg)">
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 text-center sm:mb-14" data-reveal>
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em]"
                  style="background:color-mix(in srgb, var(--site-gold) 15%, transparent); color:var(--site-gold)">Leadership</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-stone-900 sm:text-4xl">Leadership Message Desk</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-stone-500">A warm welcome from the leadership guiding our institution.</p>
        </div>

        <div class="mx-auto flex max-w-4xl flex-col gap-5">
            @foreach($leaders as $L)
                <a href="{{ route('leadership.message', $L) }}" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}"
                   class="group grid grid-cols-[130px_1fr] overflow-hidden rounded-2xl bg-white shadow-[0_10px_30px_-18px_rgba(15,27,46,0.2)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_42px_-16px_rgba(15,27,46,0.28)] sm:grid-cols-[190px_1fr]">
                    <div class="relative" style="background:linear-gradient(160deg,var(--site-brand),var(--site-brand-dark))">
                        @if($L->photo_url)
                            <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <span class="font-display text-2xl font-bold text-white/90">{{ $initialsFor($L->name) ?: 'JD' }}</span>
                            </div>
                        @endif
                        <span class="absolute inset-y-0 right-0 w-[3px]" style="background:var(--site-gold)"></span>
                    </div>
                    <div class="flex flex-col justify-center p-5 sm:p-6">
                        <span class="inline-block w-fit rounded-full px-2.5 py-1 text-[10.5px] font-bold uppercase tracking-[0.1em]"
                              style="background:color-mix(in srgb, var(--site-gold) 22%, transparent); color:var(--site-brand)">{{ $L->designation }}</span>
                        <h3 class="mt-2 font-display text-base font-bold text-stone-900 sm:text-lg">{{ $L->name }}</h3>
                        @if($L->organization)
                            <p class="text-[11.5px] text-stone-400">{{ $L->organization }}</p>
                        @endif
                        <p class="mt-2 text-[13px] leading-relaxed text-stone-600 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($L->message), 160) }}</p>
                        <span class="mt-2.5 inline-flex w-fit items-center gap-1 text-xs font-bold" style="color:var(--site-brand)">
                            Read Full Message
                            <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
