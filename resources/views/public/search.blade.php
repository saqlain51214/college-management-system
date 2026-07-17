@extends('layouts.public')
@section('title', $q ? 'Search: ' . $q : 'Search')

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-4xl">
            <h1 class="text-2xl font-bold text-white mb-4">Search</h1>
            <form action="{{ route('search') }}" method="GET" class="flex gap-2">
                <input type="search" name="q" value="{{ $q }}" placeholder="Search news, notices, events, departments…"
                       class="flex-1 rounded-lg px-4 py-2.5 text-sm bg-white/15 text-white placeholder-white/50 border border-white/25 focus:outline-none focus:bg-white/20 focus:border-white/50 transition">
                <button type="submit" class="shrink-0 rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90" style="background:var(--site-gold)">Search</button>
            </form>
        </div>
    </div>
</div>

<div class="mx-auto max-w-4xl px-4 sm:px-6 py-10">
    @if(strlen($q) < 2)
        <p class="text-stone-500 text-sm">Enter at least 2 characters to search.</p>
    @elseif($results->isEmpty())
        <div class="rounded-2xl border border-dashed border-stone-200 py-16 text-center">
            <svg class="w-10 h-10 mx-auto text-stone-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <p class="font-medium text-stone-500">No results for <span class="font-bold">"{{ $q }}"</span></p>
            <p class="text-sm text-stone-400 mt-1">Try different keywords or check spelling.</p>
        </div>
    @else
        <p class="text-sm text-stone-400 mb-6">{{ $results->count() }} result{{ $results->count() !== 1 ? 's' : '' }} for <span class="font-semibold text-stone-700">"{{ $q }}"</span></p>

        <div class="space-y-3">
            @foreach($results as $r)
            <a href="{{ $r['url'] }}"
               class="group flex items-start gap-4 rounded-xl border border-stone-200 bg-white px-5 py-4 hover:shadow-md hover:border-stone-300 transition">
                <span class="shrink-0 mt-0.5 rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white"
                      style="background:{{ match($r['type']) { 'News' => 'var(--site-brand)', 'Event' => 'var(--site-gold)', 'Notice' => '#64748b', 'Department' => '#0891b2', default => '#6b7280' } }}">
                    {{ $r['type'] }}
                </span>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-stone-800 text-sm leading-snug group-hover:text-[var(--site-brand)] transition">{{ $r['title'] }}</h3>
                    @if($r['excerpt'])
                        <p class="text-xs text-stone-500 mt-1 line-clamp-2">{{ $r['excerpt'] }}</p>
                    @endif
                </div>
                @if($r['date'])
                <span class="shrink-0 text-xs text-stone-400">{{ $r['date'] }}</span>
                @endif
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
