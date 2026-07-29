{{-- Leadership Spotlight — dark, editorial. One featured leader (image +
     pull-quote) plus the rest as a compact row. --}}
@php
    $featured = $leaders->first();
    $rest = $leaders->slice(1);
@endphp
<section class="relative overflow-hidden py-16 sm:py-24" style="background:linear-gradient(160deg,#0b1220,#111c33 55%,#0b1220)">
    <div class="pointer-events-none absolute inset-0 opacity-[0.05]" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:26px 26px"></div>
    <div class="pointer-events-none absolute right-[-8%] top-[-15%] h-[28rem] w-[28rem] rounded-full opacity-20 blur-3xl" style="background:var(--site-gold)"></div>
    <div class="pointer-events-none absolute left-[-12%] bottom-[-20%] h-[26rem] w-[26rem] rounded-full opacity-10 blur-3xl" style="background:var(--site-brand)"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-12 text-center sm:mb-16" data-reveal>
            <span class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.25em]"
                  style="border-color:color-mix(in srgb, var(--site-gold) 40%, transparent); color:var(--site-gold)">Leadership</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-white sm:text-4xl">Leadership Message Desk</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-white/60">A warm welcome from the leadership guiding our institution.</p>
        </div>

        @if($featured)
        <a href="{{ route('leadership.message', $featured) }}" data-reveal
           class="group grid grid-cols-1 items-center gap-10 rounded-[32px] border border-white/10 bg-white/[0.04] p-6 backdrop-blur-sm transition-colors duration-300 hover:border-white/20 sm:p-10 lg:grid-cols-[380px_1fr] lg:gap-14 lg:p-12">
            <div class="relative mx-auto w-full max-w-xs lg:max-w-none">
                <div class="pointer-events-none absolute -inset-3 rounded-[28px] border" style="border-color:color-mix(in srgb, var(--site-gold) 35%, transparent)"></div>
                <div class="relative aspect-[4/5] w-full overflow-hidden rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.6)]">
                    @if($featured->photo_url)
                        <img src="{{ $featured->photo_url }}" alt="{{ $featured->name }}" loading="lazy"
                             class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center" style="background:linear-gradient(135deg,var(--site-brand),#0b1220)">
                            <span class="font-display text-5xl font-bold text-white/90">{{ $initialsFor($featured->name) ?: 'JD' }}</span>
                        </div>
                    @endif
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                </div>
            </div>

            <div class="text-center lg:text-left">
                <svg class="mx-auto mb-4 h-9 w-9 opacity-70 lg:mx-0" fill="currentColor" viewBox="0 0 32 32" style="color:var(--site-gold)">
                    <path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/>
                </svg>
                <p class="font-display text-xl italic leading-relaxed text-white/90 sm:text-2xl">
                    &ldquo;{{ \Illuminate\Support\Str::limit(strip_tags($featured->message), 260) }}&rdquo;
                </p>

                <div class="mt-8">
                    <h3 class="font-display text-2xl font-bold text-white">{{ $featured->name }}</h3>
                    <p class="mt-1 text-sm font-bold uppercase tracking-[0.15em]" style="color:var(--site-gold)">{{ $featured->designation }}</p>
                    @if($featured->organization)
                        <p class="mt-0.5 text-sm text-white/50">{{ $featured->organization }}</p>
                    @endif
                </div>

                <span class="mt-7 inline-flex items-center gap-2 rounded-full border px-6 py-2.5 text-sm font-semibold text-white transition-all duration-300 group-hover:gap-3"
                      style="border-color:color-mix(in srgb, var(--site-gold) 50%, transparent)">
                    Read Full Message
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </div>
        </a>
        @endif

        @if($rest->isNotEmpty())
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
            @foreach($rest as $L)
                <a href="{{ route('leadership.message', $L) }}" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}"
                   class="group flex items-center gap-4 rounded-2xl border border-white/10 bg-white/[0.03] p-4 transition-all duration-300 hover:-translate-y-1 hover:border-white/20 hover:bg-white/[0.06]">
                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-full ring-2" style="--tw-ring-color:color-mix(in srgb, var(--site-gold) 40%, transparent)">
                        @if($L->photo_url)
                            <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center" style="background:linear-gradient(135deg,var(--site-brand),#0b1220)">
                                <span class="text-base font-bold text-white/90">{{ $initialsFor($L->name) ?: 'JD' }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="truncate font-display text-sm font-bold text-white">{{ $L->name }}</h4>
                        <p class="truncate text-xs font-semibold" style="color:var(--site-gold)">{{ $L->designation }}</p>
                    </div>
                    <svg class="h-4 w-4 flex-shrink-0 text-white/30 transition-all group-hover:translate-x-0.5 group-hover:text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            @endforeach
        </div>
        @endif
    </div>
</section>
