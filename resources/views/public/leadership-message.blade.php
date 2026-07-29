@extends('layouts.public')
@section('title', $message->designation . ' — Message — ' . ($college->college_name ?? 'JDCA'))

@section('content')
@php $initials = collect(explode(' ', trim($message->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''); @endphp
<div class="pb-14" style="padding-top: var(--site-header-offset, 6rem); background:var(--site-body-bg);">

    <div class="mx-auto max-w-5xl px-4 pt-8 sm:px-6">
        <p class="text-xs text-stone-400">
            <a href="{{ route('home') }}" class="hover:text-stone-700">Home</a>
            <span class="mx-1.5">›</span>
            <span class="font-semibold text-stone-600">Message from the {{ $message->designation }}</span>
        </p>
    </div>

    {{-- Large portrait panel + message — photo and text never overlap --}}
    <div class="mx-auto mt-6 max-w-5xl px-4 sm:px-6">
        <div class="grid overflow-hidden rounded-[28px] bg-white shadow-[0_16px_44px_-20px_rgba(15,27,46,0.24)] lg:grid-cols-[340px_1fr]">
            {{-- Portrait --}}
            <div class="relative" style="background:linear-gradient(160deg,var(--site-brand),var(--site-brand-dark))">
                <div class="relative aspect-[3/4] w-full lg:aspect-auto lg:h-full lg:min-h-[420px]">
                    @if($message->photo_url)
                        <img src="{{ $message->photo_url }}" alt="{{ $message->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center">
                            <span class="font-display text-6xl font-bold text-white/90">{{ $initials ?: 'JD' }}</span>
                        </div>
                    @endif
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                        <h2 class="font-display text-2xl font-bold text-white">{{ $message->name }}</h2>
                        <p class="mt-0.5 text-xs font-bold uppercase tracking-[0.1em]" style="color:var(--site-gold)">{{ $message->designation }}</p>
                    </div>
                </div>
            </div>

            {{-- Message body --}}
            <div class="p-8 sm:p-10">
                <svg class="h-9 w-9 opacity-40" style="color:var(--site-brand)" fill="currentColor" viewBox="0 0 24 24"><path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/></svg>

                <div class="prose prose-stone mt-4 max-w-none leading-relaxed text-stone-700">
                    @foreach(preg_split('/\n+/', $message->message) as $para)
                        @if(trim($para) !== '')<p class="mb-4 text-[15.5px]">{{ trim($para) }}</p>@endif
                    @endforeach
                </div>

                @if($message->organization)
                    <span class="mt-2 inline-block rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.08em]"
                          style="background:color-mix(in srgb, var(--site-gold) 18%, transparent); color:var(--site-brand)">{{ $message->organization }}</span>
                @endif

                <div class="mt-8 border-t border-stone-100 pt-6 text-right">
                    <p class="font-display text-lg font-bold text-stone-900">{{ $message->name }}</p>
                    <p class="text-sm font-semibold" style="color:var(--site-gold)">{{ $message->designation }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Other leaders --}}
    @if($others->isNotEmpty())
    <div class="mx-auto mt-12 max-w-5xl px-4 sm:px-6">
        <p class="mb-5 text-center text-xs font-bold uppercase tracking-[0.2em]" style="color:var(--site-gold)">More from Leadership</p>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($others as $o)
                @php $oi = collect(explode(' ', trim($o->name)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''); @endphp
                <a href="{{ route('leadership.message', $o) }}" class="group flex items-center gap-3 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-stone-100 transition hover:-translate-y-1 hover:shadow-md">
                    <span class="h-14 w-14 shrink-0 overflow-hidden rounded-full" style="background:linear-gradient(135deg,var(--site-brand),var(--site-gold))">
                        @if($o->photo_url)<img src="{{ $o->photo_url }}" class="h-full w-full object-cover" alt="{{ $o->name }}">@else<span class="flex h-full w-full items-center justify-center text-sm font-bold text-white">{{ $oi }}</span>@endif
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-bold text-stone-800">{{ $o->name }}</span>
                        <span class="block truncate text-xs" style="color:var(--site-gold)">{{ $o->designation }}</span>
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
@endsection
