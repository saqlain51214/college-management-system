@php
    $eventsSection = $pageContent['events'] ?? [];
@endphp

<section class="bg-surface py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        <div class="mb-10 text-center md:mb-12">
            <h2 class="mb-3 font-display text-2xl font-semibold tracking-tight text-ink sm:text-3xl md:text-[2rem]">{{ $eventsSection['section_title'] ?? 'Events' }}</h2>
            <div class="mx-auto mb-4 flex justify-center" aria-hidden="true">
                <div class="h-1 w-16 rounded-full bg-gradient-to-r from-brand to-brand-dark sm:w-20"></div>
            </div>
            <p class="mx-auto max-w-2xl text-sm leading-relaxed text-stone-600 md:text-base">
                {{ $eventsSection['section_text'] ?? 'Upcoming dates for academics, sports, arts, and community.' }}
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 md:gap-6">
            @forelse($events as $event)
                <div class="flex overflow-hidden rounded-xl border border-slate-200/60 bg-white/95 shadow-md shadow-stone-900/[0.06] backdrop-blur-sm">
                    <div
                        class="flex min-w-[5.5rem] flex-col items-center justify-center bg-brand px-4 py-8 text-white">
                        <span class="text-3xl font-bold leading-none">{{ optional($event->start_datetime)->format('d') }}</span>
                        <span class="mt-1 text-[10px] font-bold uppercase tracking-widest">{{ optional($event->start_datetime)->format('M') }}</span>
                    </div>

                    <div class="p-6 flex-1">
                        <span class="bg-blue-100 text-blue-600 text-xs px-3 py-1 rounded-full">
                            {{ $event->organizer ?? 'Event' }}
                        </span>

                        <h3 class="mt-3 mb-2 text-left font-sans text-lg font-bold text-ink md:text-xl">
                            {{ $event->title }}
                        </h3>

                        <p class="mb-4 text-left text-sm leading-relaxed text-stone-600">
                            {{ \Illuminate\Support\Str::limit($event->description ?? 'Join us for this upcoming event.', 120) }}
                        </p>

                        <div class="flex flex-wrap gap-4 text-sm text-stone-500 mb-4">
                            <span>🕒 {{ optional($event->start_datetime)->format('h:i A') }}@if($event->end_datetime) - {{ optional($event->end_datetime)->format('h:i A') }}@endif</span>
                            <span>📍 {{ $event->venue ?? 'Campus Venue' }}</span>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('events') }}"
                                class="inline-flex items-center justify-center rounded-md bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-stone-300 bg-white p-8 text-center text-sm text-stone-500">
                    No upcoming events are published yet.
                </div>
            @endforelse

        </div>

        <div class="mt-8 text-center md:mt-9">
            <a href="{{ route('events') }}#events"
                class="inline-flex items-center justify-center rounded-md bg-brand px-8 py-3 text-sm font-semibold text-white transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 sm:text-base">
                {{ $eventsSection['button_text'] ?? 'View all events' }}
            </a>
        </div>

    </div>
</section>
