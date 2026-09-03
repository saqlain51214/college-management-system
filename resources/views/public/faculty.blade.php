@extends('layouts.public')
@section('title', 'Faculty & Staff — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523240795612-9a054b0db644.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">Faculty & Staff</span>
    </nav>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Faculty & Staff' }}</h1>
    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? 'Meet our team of qualified and dedicated educators committed to academic excellence.' }}</p>
  </div>
</section>

@php $customBody = !empty($cmsPage) ? $cmsPage->customBodyHtml() : null; @endphp
@if($customBody)
<section class="border-b border-stone-200/80 bg-white py-10 md:py-12">
  <div class="mx-auto max-w-4xl px-4 sm:px-6">
    <div class="prose prose-stone max-w-none">
      {!! $customBody !!}
    </div>
  </div>
</section>
@endif

<section class="py-12 md:py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    @if($teachers->isEmpty())
    <div class="text-center text-stone-400 py-20 rounded-xl border border-stone-200/80 bg-white shadow-md">Faculty directory will be available soon.</div>
    @else
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($teachers as $teacher)
      @php
        $qualificationLabel = $teacher->highest_qualification
          ? \App\Models\ListItem::getLabel('teacher_qualification', $teacher->highest_qualification, $teacher->highest_qualification)
          : null;
        $designationLabel = $teacher->designation
          ? \App\Models\ListItem::getLabel('teacher_designation', $teacher->designation, $teacher->designation)
          : null;
      @endphp
      <article class="group overflow-hidden rounded-2xl border border-stone-200/80 bg-white shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">
        <div class="relative bg-gradient-to-br from-brand/15 via-brand/5 to-transparent pt-10 pb-6 px-4 text-center">
          @if($teacher->photo)
          <img src="{{ asset('storage/'.$teacher->photo) }}" alt="{{ $teacher->name }}"
               class="mx-auto h-28 w-28 rounded-full border-4 border-white object-cover shadow-lg ring-1 ring-brand/20 transition duration-300 group-hover:scale-105">
          @else
          <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full border-4 border-white bg-brand text-3xl font-bold text-white shadow-lg ring-1 ring-brand/20">
            {{ strtoupper(substr($teacher->name, 0, 1)) }}
          </div>
          @endif

          @if($designationLabel)
          <div class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-brand px-4 py-1 text-xs font-semibold tracking-wide text-white shadow-sm">
            {{ $designationLabel }}
          </div>
          @endif
        </div>

        <div class="space-y-3 px-5 pb-6 pt-5 text-center">
          <h3 class="font-display text-lg font-bold text-ink">{{ $teacher->name }}</h3>

          @if($teacher->department?->name)
          <p class="text-sm text-stone-500">{{ $teacher->department->name }}</p>
          @endif

          @if($teacher->specialization)
          <div class="inline-flex items-center gap-1.5 rounded-full bg-brand/10 px-3 py-1 text-xs font-semibold text-brand">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
            {{ $teacher->specialization }}
          </div>
          @endif

          <div class="!mt-4 flex flex-col gap-2 border-t border-stone-100 pt-4 text-left text-sm text-stone-600">
            @if($qualificationLabel)
            <div class="flex items-center gap-2.5">
              <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand/10 text-brand">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443"/></svg>
              </span>
              <span class="font-medium text-ink">{{ $qualificationLabel }}</span>
            </div>
            @endif

            @if($teacher->professional_qualification)
            <div class="flex items-center gap-2.5">
              <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand/10 text-brand">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
              </span>
              <span class="font-medium text-ink">{{ $teacher->professional_qualification }}</span>
              <span class="text-xs text-stone-400">(Professional)</span>
            </div>
            @endif

            @if($teacher->experience_years)
            <div class="flex items-center gap-2.5">
              <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand/10 text-brand">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
              </span>
              <span>{{ $teacher->experience_years }} {{ \Illuminate\Support\Str::plural('year', $teacher->experience_years) }} experience</span>
            </div>
            @endif

            @if($teacher->email)
            <div class="flex items-center gap-2.5">
              <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-brand/10 text-brand">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
              </span>
              <a href="mailto:{{ $teacher->email }}" class="truncate text-brand hover:underline" title="{{ $teacher->email }}">{{ $teacher->email }}</a>
            </div>
            @endif
          </div>
        </div>
      </article>
      @endforeach
    </div>
    @endif
  </div>
</section>

<section class="border-t border-stone-200/80 bg-brand py-12 text-center text-white md:py-14">
  <div class="mx-auto max-w-2xl px-4 sm:px-6">
    <h2 class="font-display text-2xl font-semibold sm:text-3xl">Join our team</h2>
    <p class="mt-3 text-sm text-white/90 sm:text-base">We are always looking for qualified educators passionate about teaching.</p>
    <div class="mt-6">
      <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-md bg-white px-5 py-2.5 text-sm font-semibold text-brand shadow-lg transition hover:bg-stone-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">Contact us</a>
    </div>
  </div>
</section>

@endsection
