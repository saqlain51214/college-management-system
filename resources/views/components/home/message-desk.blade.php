@php
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
@endphp

@if($leaders->isNotEmpty())
<section class="relative overflow-hidden py-16 sm:py-24" style="background:var(--site-body-bg)">
    {{-- soft decorative accents --}}
    <div class="pointer-events-none absolute right-[-10%] top-[-10%] h-96 w-96 rounded-full opacity-[0.04]" style="background:var(--site-brand)"></div>
    <div class="pointer-events-none absolute left-[-10%] bottom-[-10%] h-96 w-96 rounded-full opacity-[0.05]" style="background:var(--site-gold)"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-12 text-center">
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em]"
                  style="background:color-mix(in srgb, var(--site-gold) 15%, transparent); color:var(--site-gold)">Leadership</span>
            <h2 class="mt-4 font-display text-3xl font-bold text-stone-900 sm:text-4xl">Message Desk</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-stone-500">A warm welcome from the leadership guiding our institution.</p>
        </div>

        <div class="grid gap-6 sm:gap-7 md:grid-cols-2 lg:grid-cols-3">
            @foreach($leaders as $L)
                @php $initials = collect(explode(' ', trim($L->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''); @endphp
                <a href="{{ route('leadership.message', $L) }}"
                   class="group relative flex flex-col overflow-hidden rounded-[26px] bg-white shadow-[0_10px_40px_-15px_rgba(0,0,0,0.15)] ring-1 ring-stone-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.28)]">
                    {{-- gradient banner + avatar --}}
                    <div class="relative h-24 site-brand-gradient">
                        <div class="pointer-events-none absolute inset-0 opacity-20" style="background:radial-gradient(circle at 30% 20%, var(--site-gold), transparent 60%)"></div>
                        <div class="absolute -bottom-10 left-1/2 -translate-x-1/2">
                            <div class="h-20 w-20 rounded-full p-[3px]" style="background:linear-gradient(135deg,var(--site-gold),#ffffff)">
                                <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-full bg-white ring-1 ring-black/5">
                                    @if($L->photo_url)
                                        <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="font-display text-xl font-bold" style="color:var(--site-brand)">{{ $initials ?: 'JD' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-col px-6 pb-6 pt-12 text-center">
                        <h3 class="font-display text-lg font-bold text-stone-900">{{ $L->name }}</h3>
                        <p class="text-sm font-semibold" style="color:var(--site-gold)">{{ $L->designation }}</p>
                        @if($L->organization)
                            <p class="text-xs text-stone-400">{{ $L->organization }}</p>
                        @endif

                        <p class="mt-4 flex-1 text-sm leading-relaxed text-stone-600 line-clamp-4">{{ Str::limit(strip_tags($L->message), 180) }}</p>

                        <span class="mt-5 inline-flex items-center justify-center gap-1.5 text-sm font-semibold transition-all group-hover:gap-2.5"
                              style="color:var(--site-brand)">
                            Read Full Message
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
