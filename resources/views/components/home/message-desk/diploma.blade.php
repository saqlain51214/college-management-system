{{-- Diploma Frame — ornamental double-border frame (echoes a diploma/seal),
     square photo, small-caps serif name, gold rule, italic message. --}}
<section class="relative overflow-hidden py-14 sm:py-20" style="background:var(--site-body-bg)">
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
                   class="group relative rounded-lg bg-white p-9 pb-7 text-center shadow-[0_8px_28px_-16px_rgba(15,27,46,0.18)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_18px_40px_-16px_rgba(15,27,46,0.26)]">
                    <span class="pointer-events-none absolute inset-2.5 rounded-sm border" style="border-color:var(--site-gold)"></span>
                    <span class="pointer-events-none absolute inset-3.5 rounded-sm border opacity-15" style="border-color:var(--site-brand)"></span>

                    <div class="relative mx-auto h-32 w-32 overflow-hidden rounded-lg" style="border:2px solid var(--site-brand); box-shadow:0 0 0 4px #fff, 0 0 0 5px var(--site-gold)">
                        @if($L->photo_url)
                            <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center" style="background:linear-gradient(150deg,var(--site-brand),var(--site-brand-dark))">
                                <span class="font-display text-3xl font-bold text-white/90">{{ $initialsFor($L->name) ?: 'JD' }}</span>
                            </div>
                        @endif
                    </div>

                    <svg class="mx-auto mb-2 mt-3 h-6 w-6" style="color:var(--site-gold)" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M8.5 11.5L7 21l5-3 5 3-1.5-9.5"/>
                    </svg>

                    <h3 class="font-display text-lg tracking-wide text-stone-900">{{ $L->name }}</h3>
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em]" style="color:var(--site-gold)">{{ $L->designation }}</p>
                    @if($L->organization)
                        <p class="mt-0.5 text-[11.5px] text-stone-400">{{ $L->organization }}</p>
                    @endif

                    <span class="mx-auto my-4 block h-0.5 w-9" style="background:var(--site-gold)"></span>

                    <p class="font-display text-[13.5px] italic leading-relaxed text-stone-600 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($L->message), 170) }}</p>

                    <span class="mt-4 inline-block border-b pb-0.5 text-xs font-bold uppercase tracking-[0.08em]" style="color:var(--site-brand); border-color:var(--site-gold)">Read Full Message</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
