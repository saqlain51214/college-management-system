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
<section class="relative overflow-hidden py-14 sm:py-20" style="background:var(--site-body-bg)">
    <div class="pointer-events-none absolute -right-24 top-10 h-72 w-72 rounded-full opacity-[0.05]" style="background:var(--site-brand)"></div>
    <div class="pointer-events-none absolute -left-24 bottom-0 h-72 w-72 rounded-full opacity-[0.05]" style="background:var(--site-gold)"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
        <div class="mb-10 sm:mb-14 text-center">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.22em]" style="color:var(--site-gold)">Leadership</p>
            <h2 class="font-display text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">Message Desk</h2>
            <div class="mx-auto mt-3 h-1 w-16 rounded-full" style="background:var(--site-gold)"></div>
        </div>

        <div class="grid gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($leaders as $L)
                @php
                    $initials = collect(explode(' ', trim($L->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
                @endphp
                <article class="group relative flex flex-col items-center rounded-3xl bg-white p-7 text-center shadow-sm ring-1 ring-stone-100 transition duration-300 hover:-translate-y-1.5 hover:shadow-xl">
                    {{-- accent top bar --}}
                    <span class="absolute inset-x-10 top-0 h-1 rounded-full site-gold-gradient"></span>

                    {{-- Photo --}}
                    <div class="mb-4 mt-2 h-28 w-28 overflow-hidden rounded-full p-1" style="background:linear-gradient(135deg,var(--site-brand),var(--site-gold))">
                        <div class="h-full w-full overflow-hidden rounded-full bg-white flex items-center justify-center">
                            @if($L->photo_url)
                                <img src="{{ $L->photo_url }}" alt="{{ $L->name }}" class="h-full w-full object-cover">
                            @else
                                <span class="font-display text-2xl font-bold" style="color:var(--site-brand)">{{ $initials ?: 'JD' }}</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="font-display text-lg font-bold text-stone-900">{{ $L->name }}</h3>
                    <p class="text-sm font-semibold" style="color:var(--site-gold)">{{ $L->designation }}</p>
                    @if($L->organization)
                        <p class="text-xs text-stone-400">{{ $L->organization }}</p>
                    @endif

                    <svg class="mx-auto my-3 h-6 w-6 opacity-25" style="color:var(--site-brand)" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/></svg>
                    <p class="text-sm leading-relaxed text-stone-600">{{ Str::limit(strip_tags($L->message), 260) }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
