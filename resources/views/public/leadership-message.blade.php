@extends('layouts.public')
@section('title', $message->designation . ' — Message — ' . ($college->college_name ?? 'JDCA'))

@section('content')
@php $initials = collect(explode(' ', trim($message->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''); @endphp
<div style="padding-top: var(--site-header-offset, 6rem);">

    {{-- Hero --}}
    <div class="site-brand-gradient py-10 px-4 sm:px-6">
        <div class="mx-auto max-w-5xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Message from the {{ $message->designation }}</span>
            </p>
            <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold text-white">Message from the {{ $message->designation }}</h1>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 py-12">
        <div class="grid gap-8 lg:grid-cols-[300px_1fr] lg:gap-12">
            {{-- Profile card --}}
            <aside class="lg:sticky lg:top-28 h-fit">
                <div class="overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-stone-100">
                    <div class="h-28 site-brand-gradient"></div>
                    <div class="-mt-16 px-6 pb-6 text-center">
                        <div class="mx-auto h-28 w-28 rounded-full p-[3px]" style="background:linear-gradient(135deg,var(--site-gold),#fff)">
                            <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-full bg-white ring-1 ring-black/5">
                                @if($message->photo_url)
                                    <img src="{{ $message->photo_url }}" alt="{{ $message->name }}" class="h-full w-full object-cover">
                                @else
                                    <span class="font-display text-3xl font-bold" style="color:var(--site-brand)">{{ $initials ?: 'JD' }}</span>
                                @endif
                            </div>
                        </div>
                        <h2 class="mt-4 font-display text-xl font-bold text-stone-900">{{ $message->name }}</h2>
                        <p class="text-sm font-semibold" style="color:var(--site-gold)">{{ $message->designation }}</p>
                        @if($message->organization)<p class="text-xs text-stone-400 mt-0.5">{{ $message->organization }}</p>@endif
                    </div>
                </div>
            </aside>

            {{-- Message body --}}
            <article>
                <svg class="mb-4 h-12 w-12 opacity-20" style="color:var(--site-brand)" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/></svg>
                <div class="prose prose-stone max-w-none leading-relaxed text-stone-700">
                    @foreach(preg_split('/\n+/', $message->message) as $para)
                        @if(trim($para) !== '')<p class="mb-4 text-[15.5px]">{{ trim($para) }}</p>@endif
                    @endforeach
                </div>

                <div class="mt-8 border-t border-stone-100 pt-6 text-right">
                    <p class="font-display text-lg font-bold text-stone-900">{{ $message->name }}</p>
                    <p class="text-sm" style="color:var(--site-gold)">{{ $message->designation }}</p>
                </div>
            </article>
        </div>

        {{-- Other leaders --}}
        @if($others->isNotEmpty())
        <div class="mt-14 border-t border-stone-100 pt-10">
            <p class="mb-5 text-center text-xs font-bold uppercase tracking-[0.2em]" style="color:var(--site-gold)">More from Leadership</p>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($others as $o)
                    @php $oi = collect(explode(' ', trim($o->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''); @endphp
                    <a href="{{ route('leadership.message', $o) }}" class="group flex items-center gap-3 rounded-2xl bg-white p-4 ring-1 ring-stone-100 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full" style="background:linear-gradient(135deg,var(--site-brand),var(--site-gold))">
                            @if($o->photo_url)<img src="{{ $o->photo_url }}" class="h-full w-full object-cover" alt="{{ $o->name }}">@else<span class="text-sm font-bold text-white">{{ $oi }}</span>@endif
                        </span>
                        <span class="min-w-0">
                            <span class="block text-sm font-bold text-stone-800 truncate">{{ $o->name }}</span>
                            <span class="block text-xs" style="color:var(--site-gold)">{{ $o->designation }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('home') }}" class="text-sm font-semibold text-stone-500 hover:text-stone-800">← Back to Home</a>
        </div>
    </div>
</div>
@endsection
