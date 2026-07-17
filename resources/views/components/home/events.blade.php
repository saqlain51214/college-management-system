@php $eventsSection = $pageContent['events'] ?? []; @endphp

<section class="py-20 sm:py-28" style="background:#f5f2ed" aria-labelledby="events-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.16em]" style="color:var(--site-gold)">Academic Calendar</p>
                <h2 id="events-heading" class="font-display text-3xl font-bold text-stone-900 sm:text-4xl">
                    {{ $eventsSection['section_title'] ?? 'Upcoming Events' }}
                </h2>
            </div>
            <a href="{{ route('events') }}" class="shrink-0 text-sm font-semibold transition hover:opacity-75" style="color: var(--site-brand)">
                Full calendar →
            </a>
        </div>

        {{-- Events list --}}
        <div class="space-y-4">
            @forelse($events as $event)
            <div class="group grid grid-cols-[5rem_1fr] items-start gap-5 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-200/70 transition hover:shadow-md sm:grid-cols-[6rem_1fr] sm:p-6 md:items-center">

                {{-- Date block --}}
                <div class="flex flex-col items-center justify-center rounded-xl py-3 text-white" style="background: var(--site-brand)">
                    <span class="font-display text-2xl font-bold leading-none sm:text-3xl">
                        {{ optional($event->start_datetime)->format('d') ?? '--' }}
                    </span>
                    <span class="mt-1 text-[10px] font-bold uppercase tracking-widest text-white/75">
                        {{ optional($event->start_datetime)->format('M Y') ?? '' }}
                    </span>
                </div>

                {{-- Event details --}}
                <div class="min-w-0 flex flex-col md:flex-row md:items-center md:gap-8">
                    <div class="flex-1 min-w-0">
                        @if($event->organizer)
                        <span class="mb-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white" style="background: var(--site-gold)">
                            {{ $event->organizer }}
                        </span>
                        @endif
                        <h3 class="mt-1 font-display text-base font-bold text-stone-900 sm:text-lg">{{ $event->title }}</h3>
                        <p class="mt-1 text-sm text-stone-500 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit($event->description ?? 'Join us for this upcoming event.', 120) }}
                        </p>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-stone-400 md:mt-0 md:shrink-0 md:flex-col md:items-end md:gap-1">
                        @if($event->start_datetime)
                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $event->start_datetime->format('g:i A') }}@if($event->end_datetime) – {{ $event->end_datetime->format('g:i A') }}@endif
                        </span>
                        @endif
                        @if($event->venue)
                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $event->venue }}
                        </span>
                        @endif
                        <a href="{{ route('events') }}"
                           class="event-details-btn rounded-lg border px-4 py-1.5 text-xs font-semibold transition"
                           style="border-color:var(--site-brand); color:var(--site-brand)">
                            Details
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="rounded-2xl border border-dashed border-stone-300 bg-white py-16 text-center">
                <svg class="mx-auto mb-3 h-8 w-8 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm text-stone-400">No upcoming events published yet.</p>
            </div>
            @endforelse
        </div>

    </div>
</section>
