@php
    use App\Models\CollegeSetting;
    use App\Models\LeadershipMessage;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Str;

    $leaders = collect();
    try {
        if (Schema::hasTable('leadership_messages')) {
            $leaders = LeadershipMessage::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        }
    } catch (\Throwable) {
        $leaders = collect();
    }

    $layout = CollegeSetting::get('message_desk_layout', 'card');
@endphp

@if($leaders->isNotEmpty())
<section class="relative overflow-hidden py-14 sm:py-20" style="background:var(--site-body-bg)">
    {{-- soft decorative accents --}}
    <div class="pointer-events-none absolute right-[-10%] top-[-10%] h-96 w-96 rounded-full opacity-10 blur-3xl" style="background:var(--site-brand)"></div>
    <div class="pointer-events-none absolute left-[-10%] bottom-[-10%] h-96 w-96 rounded-full opacity-10 blur-3xl" style="background:var(--site-gold)"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 text-center sm:mb-14" data-reveal>
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em]"
                  style="background:color-mix(in srgb, var(--site-gold) 15%, transparent); color:var(--site-gold)">Leadership</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-stone-900 sm:text-4xl">Message Desk</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-stone-500">A warm welcome from the leadership guiding our institution.</p>
        </div>

        @if($layout === 'side_by_side')
            {{-- ── Side-by-Side layout: full portrait next to the message ── --}}
            <div class="space-y-6">
                @foreach($leaders as $L)
                    <a href="{{ route('leadership.message', $L) }}" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}"
                       class="group grid grid-cols-1 overflow-hidden rounded-[28px] bg-white shadow-[0_10px_40px_-18px_rgba(0,0,0,0.18)] ring-1 ring-stone-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.26)] sm:grid-cols-[220px_1fr] md:grid-cols-[260px_1fr]">
                        {{-- Photo panel --}}
                        <div class="relative aspect-[4/3] overflow-hidden sm:aspect-auto">
                            @php $initials = collect(explode(' ', trim($L->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''); @endphp
                            @if($L->photo_url)
                                <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy"
                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center site-brand-gradient">
                                    <span class="font-display text-4xl font-bold text-white/90">{{ $initials ?: 'JD' }}</span>
                                </div>
                            @endif
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent sm:bg-gradient-to-r"></div>
                        </div>

                        {{-- Content panel --}}
                        <div class="relative flex flex-col justify-center p-6 sm:p-8">
                            <svg class="absolute right-6 top-6 h-10 w-10 opacity-[0.07]" fill="currentColor" viewBox="0 0 32 32" style="color:var(--site-brand)">
                                <path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/>
                            </svg>
                            <span class="mb-2 inline-block w-fit rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide"
                                  style="background:color-mix(in srgb, var(--site-gold) 14%, transparent); color:var(--site-gold)">
                                {{ $L->designation }}
                            </span>
                            <h3 class="font-display text-xl font-bold text-stone-900">{{ $L->name }}</h3>
                            @if($L->organization)
                                <p class="mt-0.5 text-xs font-medium text-stone-400">{{ $L->organization }}</p>
                            @endif
                            <p class="mt-4 text-sm leading-relaxed text-stone-600 line-clamp-3">{{ Str::limit(strip_tags($L->message), 220) }}</p>
                            <span class="mt-4 inline-flex w-fit items-center gap-1.5 text-sm font-semibold transition-all group-hover:gap-2.5"
                                  style="color:var(--site-brand)">
                                Read Full Message
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            {{-- ── Card layout: Minimal Circle Portrait — photo and text never share
                 the same space, so nothing can ever overlap or become unreadable ── --}}
            <div class="grid gap-7 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($leaders as $L)
                    @php $initials = collect(explode(' ', trim($L->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''); @endphp
                    <a href="{{ route('leadership.message', $L) }}" data-reveal data-reveal-delay="{{ $loop->index % 3 + 1 }}"
                       class="group flex flex-col items-center rounded-[26px] bg-white px-7 pb-7 pt-9 text-center shadow-[0_10px_40px_-18px_rgba(0,0,0,0.18)] ring-1 ring-stone-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.28)]">
                        {{-- Photo — a plain circle, nothing ever drawn on top of it --}}
                        <div class="h-28 w-28 flex-shrink-0 rounded-full p-[3px] transition-colors duration-300" style="background:linear-gradient(135deg,var(--site-gold),color-mix(in srgb, var(--site-gold) 40%, white))">
                            <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-full bg-white ring-4 ring-white">
                                @if($L->photo_url)
                                    <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" loading="lazy" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center site-brand-gradient">
                                        <span class="font-display text-2xl font-bold text-white/90">{{ $initials ?: 'JD' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Identity — always in its own clear space below the photo --}}
                        <h3 class="mt-5 font-display text-lg font-bold text-stone-900">{{ $L->name }}</h3>
                        <p class="text-sm font-semibold" style="color:var(--site-gold)">{{ $L->designation }}</p>
                        @if($L->organization)
                            <p class="text-xs text-stone-400">{{ $L->organization }}</p>
                        @endif

                        <span class="my-4 h-px w-10" style="background:color-mix(in srgb, var(--site-gold) 45%, transparent)"></span>

                        <svg class="mb-1 h-5 w-5 opacity-20" fill="currentColor" viewBox="0 0 32 32" style="color:var(--site-brand)">
                            <path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-1.1.9-2 2-2V8zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-1.1.9-2 2-2V8z"/>
                        </svg>
                        <p class="flex-1 text-sm leading-relaxed text-stone-600 line-clamp-4">{{ Str::limit(strip_tags($L->message), 180) }}</p>

                        <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold transition-all group-hover:gap-2.5"
                              style="color:var(--site-brand)">
                            Read Full Message
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
