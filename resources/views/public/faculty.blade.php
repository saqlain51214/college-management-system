@extends('layouts.public')
@section('title', 'Faculty & Staff')
@section('content')

<section class="relative overflow-hidden text-white" style="background:var(--c-ink); padding-top:7rem; padding-bottom:3.5rem;">
  <div class="absolute inset-0 pointer-events-none" style="opacity:.06;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:32px 32px;"></div>
  <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(to bottom,rgba(107,45,57,.35) 0%,transparent 100%);"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 flex items-center gap-1.5 text-xs" style="color:rgba(255,255,255,.50);">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span style="color:rgba(255,255,255,.80);">Faculty &amp; Staff</span>
    </nav>
    <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">Faculty &amp; Staff</h1>
    <p class="mt-3 max-w-xl text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,.80);">Meet our team of qualified and dedicated educators committed to academic excellence.</p>
  </div>
</section>

<section class="py-20 bg-gray-50">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    @if($teachers->isEmpty())
    <div class="text-center text-gray-400 py-20">Faculty directory will be available soon.</div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($teachers as $teacher)
      <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition text-center">
        <div class="bg-gradient-to-br from-navy/10 to-navy/20 py-8 px-4">
          @if($teacher->photo)
          <img src="{{ asset('storage/'.$teacher->photo) }}" alt="{{ $teacher->name }}" class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-white shadow">
          @else
          <div class="w-24 h-24 rounded-full bg-navy flex items-center justify-center text-white text-2xl font-bold mx-auto border-4 border-white shadow">
            {{ strtoupper(substr($teacher->name, 0, 1)) }}
          </div>
          @endif
        </div>
        <div class="p-5">
          <h3 class="font-bold text-gray-900 text-base">{{ $teacher->name }}</h3>
          @if($teacher->designation)
          <div class="text-gold text-xs font-semibold mt-1">{{ $teacher->designation }}</div>
          @endif
          @if($teacher->highest_qualification)
          <div class="text-gray-400 text-xs mt-1">{{ $teacher->highest_qualification }}</div>
          @endif
          @if($teacher->specialization)
          <div class="text-navy text-xs font-medium mt-2 bg-navy/5 rounded-full px-3 py-1 inline-block">{{ $teacher->specialization }}</div>
          @endif
          @if($teacher->experience_years)
          <div class="text-gray-400 text-xs mt-2">{{ $teacher->experience_years }} yrs experience</div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>

@endsection
