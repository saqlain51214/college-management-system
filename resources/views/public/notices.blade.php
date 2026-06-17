@extends('layouts.public')
@section('title', 'Notice Board — JDCA')
@section('meta_description', 'Official notices and announcements from Jinnah School & Degree College Astore (JDCA) administration.')

@section('content')

{{-- ============================================================
     SECTION 1: PAGE HERO
     ============================================================ --}}
<section class="relative overflow-hidden text-white" style="background:var(--c-ink); padding-top:7rem; padding-bottom:3.5rem;">
  <div class="absolute inset-0 pointer-events-none" style="opacity:.06;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:32px 32px;"></div>
  <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(to bottom,rgba(107,45,57,.35) 0%,transparent 100%);"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 flex items-center gap-1.5 text-xs" style="color:rgba(255,255,255,.50);">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span style="color:rgba(255,255,255,.80);">Notice Board</span>
    </nav>
    <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
      Notice <span style="color:var(--c-gold);">Board</span>
    </h1>
    <p class="mt-3 max-w-2xl text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,.80);">
      Official notices and important announcements from the administration of {{ $college->college_name ?? 'Jinnah Degree College Astore' }}.
    </p>
  </div>
</section>

{{-- ============================================================
     SECTION 2: FILTER BAR
     ============================================================ --}}
<div class="sticky z-30 bg-white shadow-sm border-b border-gray-100" style="top:0">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4">
    <div class="flex flex-wrap items-center gap-4">

      {{-- Priority filter --}}
      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs font-bold uppercase tracking-wide text-gray-400">Priority:</span>
        @php
          $priorities = ['All' => null, 'Urgent' => 'urgent', 'High' => 'high', 'Normal' => 'normal'];
          $activePriority = request('priority', 'All');
          $activeAudience = request('audience', 'All');
        @endphp
        @foreach($priorities as $label => $val)
        @php
          $pActive = ($activePriority === $label || ($label === 'All' && !request('priority')));
        @endphp
        <a href="{{ route('notices') }}?{{ http_build_query(array_filter(['priority' => $val, 'audience' => request('audience')])) }}"
           class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all"
           style="{{ $pActive ? 'background:var(--c-primary);color:#fff;border:2px solid var(--c-primary)' : 'background:transparent;color:#374151;border:2px solid #d1d5db' }}">
          {{ $label }}
        </a>
        @endforeach
      </div>

      <div class="h-5 w-px bg-gray-200 hidden sm:block"></div>

      {{-- Audience filter --}}
      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs font-bold uppercase tracking-wide text-gray-400">Audience:</span>
        @php
          $audiences = ['All' => null, 'Students' => 'students', 'Staff' => 'staff'];
        @endphp
        @foreach($audiences as $label => $val)
        @php
          $aActive = ($activeAudience === $label || ($label === 'All' && !request('audience')));
        @endphp
        <a href="{{ route('notices') }}?{{ http_build_query(array_filter(['priority' => request('priority'), 'audience' => $val])) }}"
           class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all"
           style="{{ $aActive ? 'background:var(--c-gold);color:#1a0a00;border:2px solid var(--c-gold)' : 'background:transparent;color:#374151;border:2px solid #d1d5db' }}">
          {{ $label }}
        </a>
        @endforeach
      </div>

      {{-- Clear filters --}}
      @if(request('priority') || request('audience'))
      <a href="{{ route('notices') }}"
         class="px-4 py-1.5 rounded-full text-xs font-medium transition-all ml-auto"
         style="background:rgba(90,18,18,.08);color:var(--c-primary);border:2px solid transparent">
        &#10005; Clear filters
      </a>
      @endif

    </div>
  </div>
</div>

{{-- ============================================================
     SECTION 3: NOTICES LIST
     ============================================================ --}}
<section class="py-16" style="background:var(--c-light)">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    @if(isset($notices) && $notices->isNotEmpty())

      <p class="text-sm text-gray-500 mb-6">
        Showing <strong>{{ $notices->count() }}</strong> of <strong>{{ $notices->total() }}</strong> notices
      </p>

      <div class="space-y-4" x-data>

        @foreach($notices as $notice)
        @php
          $priority = strtolower($notice->priority ?? 'normal');
          $audience = strtolower($notice->audience ?? 'everyone');
          $borderColor = match($priority) { 'urgent' => '#dc2626', 'high' => '#d97706', default => 'var(--c-primary)' };
          $isExpired = !empty($notice->valid_until) && \Carbon\Carbon::parse($notice->valid_until)->isPast();
        @endphp

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border-l-4" style="border-left-color:{{ $borderColor }}">

          <div class="p-6">

            {{-- Top row: badges + date --}}
            <div class="flex flex-wrap items-center gap-2 mb-3">

              {{-- Priority badge --}}
              @if($priority === 'urgent')
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold text-white bg-red-600">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-300 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
                Urgent
              </span>
              @elseif($priority === 'high')
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold text-white bg-amber-600">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                High
              </span>
              @else
              <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:rgba(90,18,18,.1);color:var(--c-primary)">Normal</span>
              @endif

              {{-- Audience badge --}}
              @php
                $audColor = match($audience) { 'students' => 'bg-blue-100 text-blue-800', 'staff' => 'bg-green-100 text-green-800', default => 'bg-gray-100 text-gray-600' };
              @endphp
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $audColor }}">
                {{ ucfirst($audience) }}
              </span>

              {{-- Expired badge --}}
              @if($isExpired)
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-400">Expired</span>
              @endif

              {{-- Date --}}
              @if(!empty($notice->published_at))
              <span class="ml-auto text-xs text-gray-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ \Carbon\Carbon::parse($notice->published_at)->format('d M Y') }}
              </span>
              @endif

            </div>

            {{-- Title --}}
            <h3 class="font-bold text-lg mb-2 leading-snug" style="color:var(--c-primary)">{{ $notice->title }}</h3>

            {{-- Content preview with Alpine toggle --}}
            @if(!empty($notice->content))
            <div x-data="{ expanded: false }">
              <div x-show="!expanded" class="text-sm text-gray-600 leading-relaxed">
                {{ Str::limit($notice->content, 150) }}
              </div>
              <div x-show="expanded" class="text-sm text-gray-600 leading-relaxed" style="display:none">
                {{ $notice->content }}
              </div>

              {{-- Footer row --}}
              <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">

                {{-- Valid until --}}
                @if(!empty($notice->valid_until))
                <span class="text-xs flex items-center gap-1 {{ $isExpired ? 'text-gray-400' : '' }}"
                      style="{{ !$isExpired ? 'color:var(--c-gold)' : '' }}">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ $isExpired ? 'Expired' : 'Valid until' }}:
                  {{ \Carbon\Carbon::parse($notice->valid_until)->format('d M Y') }}
                </span>
                @else
                <span></span>
                @endif

                {{-- Toggle button --}}
                @if(strlen($notice->content) > 150)
                <button @click="expanded = !expanded"
                        class="inline-flex items-center gap-1 text-xs font-semibold transition-colors"
                        style="color:var(--c-primary)">
                  <span x-text="expanded ? 'Show Less' : 'Read More'">Read More</span>
                  <svg class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''"
                       fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                @endif

              </div>
            </div>
            @endif

          </div>

          {{-- Urgent bottom stripe --}}
          @if($priority === 'urgent')
          <div class="h-1 w-full" style="background:linear-gradient(to right,#dc2626,#fca5a5,#dc2626)"></div>
          @endif

        </div>
        @endforeach

      </div>

    @elseif(request('priority') || request('audience'))
    {{-- Filtered empty state --}}
    <div class="text-center py-20">
      <div class="text-6xl mb-4">🔍</div>
      <h3 class="text-2xl font-bold mb-2" style="color:var(--c-primary)">No Notices Match Your Filters</h3>
      <p class="text-gray-500 mb-6">Try adjusting the Priority or Audience filter above.</p>
      <a href="{{ route('notices') }}"
         class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white"
         style="background:var(--c-primary)">View All Notices</a>
    </div>
    @else
    {{-- General empty state --}}
    <div class="text-center py-20">
      <div class="text-6xl mb-4">📋</div>
      <h3 class="text-2xl font-bold mb-2" style="color:var(--c-primary)">No Notices Published Yet</h3>
      <p class="text-gray-500">Official notices and announcements will appear here once published by the administration.</p>
    </div>
    @endif

  </div>
</section>

{{-- ============================================================
     SECTION 4: PAGINATION
     ============================================================ --}}
@if(isset($notices) && $notices->hasPages())
<section class="py-8 bg-white">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-sm p-4">
      {{ $notices->appends(request()->query())->links() }}
    </div>
  </div>
</section>
@endif

{{-- ============================================================
     SECTION 5: CONTACT STRIP
     ============================================================ --}}
<section class="py-14" style="background:var(--c-primary)">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

    <div class="rounded-3xl px-8 py-10" style="background:rgba(0,0,0,.2);border:1px solid rgba(255,255,255,.12)">
      <h3 class="text-2xl font-extrabold text-white mb-2">For Urgent Matters</h3>
      <p class="mb-8" style="color:rgba(255,255,255,.7)">
        Contact the college administration directly for time-sensitive enquiries.
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        @if(!empty($college->phone))
        <a href="tel:{{ $college->phone }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-white transition-transform hover:-translate-y-0.5"
           style="border:2px solid rgba(255,255,255,.5)">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
          {{ $college->phone }}
        </a>
        @endif
        @if(!empty($college->email))
        <a href="mailto:{{ $college->email }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold transition-transform hover:-translate-y-0.5"
           style="background:var(--c-gold);color:#1a0a00">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Email Us
        </a>
        @endif
      </div>
    </div>

  </div>
</section>

@endsection
