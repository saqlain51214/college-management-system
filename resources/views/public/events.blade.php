@extends('layouts.public')
@section('title', 'Events — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523240795612-9a054b0db644.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">Events</span>
    </nav>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Events' }}</h1>
    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? 'Upcoming academic, co-curricular, and campus events.' }}</p>
  </div>
</section>

@if(!empty($pageContent['body_html']))
<section class="border-b border-stone-200/80 bg-white py-10 md:py-12">
  <div class="mx-auto max-w-4xl px-4 sm:px-6">
    <div class="prose prose-stone max-w-none">
      {!! $pageContent['body_html'] !!}
    </div>
  </div>
</section>
@endif

<section class="bg-surface py-12 md:py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    @if($events->isEmpty())
      <div class="rounded-xl border border-stone-200/80 bg-white p-8 text-center text-stone-500 shadow-sm">
        No events are published right now.
      </div>
    @else
      <div class="grid gap-6 md:grid-cols-2">
        @foreach($events as $event)
          <article class="overflow-hidden rounded-xl border border-stone-200/80 bg-white shadow-sm">
            <div class="relative bg-brand px-5 py-4 text-white min-h-36 flex items-end">
              @if(!empty($event->featured_image))
                @php
                  $eventImage = str_starts_with($event->featured_image, 'assets/')
                    ? asset($event->featured_image)
                    : \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image);
                @endphp
                <img src="{{ $eventImage }}" alt="{{ $event->title }}" class="absolute inset-0 h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/30 to-ink/20"></div>
              @endif
              <p class="relative z-10 text-xs uppercase tracking-[0.2em] text-white/70">Event</p>
              <h2 class="relative z-10 mt-1 text-xl font-semibold">{{ $event->title }}</h2>
            </div>
            <div class="p-5">
              <p class="text-sm leading-relaxed text-stone-600">{{ $event->description ?? 'Visit the campus or contact the office for event details.' }}</p>
              <div class="mt-4 flex flex-wrap gap-4 text-xs text-stone-500">
                <span>Starts: {{ optional($event->start_datetime)->format('d M Y h:i A') }}</span>
                @if(!empty($event->venue))
                  <span>Venue: {{ $event->venue }}</span>
                @endif
                @if(!empty($event->organizer))
                  <span>Organizer: {{ $event->organizer }}</span>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <div class="mt-8">
        {{ $events->links() }}
      </div>
    @endif
  </div>
</section>

@endsection
