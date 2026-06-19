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

@if(!empty($pageContent['body_html']))
<section class="border-b border-stone-200/80 bg-white py-10 md:py-12">
  <div class="mx-auto max-w-4xl px-4 sm:px-6">
    <div class="prose prose-stone max-w-none">
      {!! $pageContent['body_html'] !!}
    </div>
  </div>
</section>
@endif

<section class="py-12 md:py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">
    @if($teachers->isEmpty())
    <div class="text-center text-stone-400 py-20 rounded-xl border border-stone-200/80 bg-white shadow-md">Faculty directory will be available soon.</div>
    @else
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @foreach($teachers as $teacher)
      <article class="rounded-xl border border-stone-200/80 bg-white overflow-hidden shadow-md transition hover:shadow-lg">
        <div class="bg-brand/10 py-8 px-4 text-center">
          @if($teacher->photo)
          <img src="{{ asset('storage/'.$teacher->photo) }}" alt="{{ $teacher->name }}" class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-white shadow-md">
          @else
          <div class="w-24 h-24 rounded-full bg-brand flex items-center justify-center text-white text-2xl font-bold mx-auto border-4 border-white shadow-md">
            {{ strtoupper(substr($teacher->name, 0, 1)) }}
          </div>
          @endif
        </div>
        <div class="p-5 text-center">
          <h3 class="font-semibold text-ink text-base">{{ $teacher->name }}</h3>
          @if($teacher->designation)
          <div class="text-brand text-xs font-semibold mt-1">{{ $teacher->designation }}</div>
          @endif
          @if($teacher->highest_qualification)
          <div class="text-stone-500 text-xs mt-1">{{ $teacher->highest_qualification }}</div>
          @endif
          @if($teacher->specialization)
          <div class="text-brand text-xs font-medium mt-2 bg-brand/5 rounded-full px-3 py-1 inline-block">{{ $teacher->specialization }}</div>
          @endif
          @if($teacher->experience_years)
          <div class="text-stone-500 text-xs mt-2">{{ $teacher->experience_years }} yrs experience</div>
          @endif
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
