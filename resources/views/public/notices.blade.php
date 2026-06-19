@extends('layouts.public')
@section('title', 'Notice Board — ' . ($college->college_name ?? 'JDCA'))
@section('meta_description', 'Official notices and announcements from Jinnah School & Degree College Astore (JDCA) administration.')

@section('content')

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523240795612-9a054b0db644.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">Notice Board</span>
    </nav>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Notice Board' }}</h1>
    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/90 sm:text-base">
      {{ $pageContent['intro_text'] ?? ('Official notices and important announcements from the administration of ' . ($college->college_name ?? 'Jinnah School & Degree College Astore') . '.') }}
    </p>
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

<section class="sticky z-30 bg-white border-b border-stone-200/80 shadow-sm py-4">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="flex flex-wrap items-center gap-3">
      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs font-bold uppercase tracking-wide text-stone-500">Priority:</span>
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
           style="{{ $pActive ? 'background:#6b2d39;color:#fff;border:2px solid #6b2d39' : 'background:transparent;color:#374151;border:2px solid #d1d5db' }}">
          {{ $label }}
        </a>
        @endforeach
      </div>

      <div class="h-5 w-px bg-stone-200 hidden sm:block"></div>

      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs font-bold uppercase tracking-wide text-stone-500">Audience:</span>
        @php
          $audiences = ['All' => null, 'Students' => 'students', 'Staff' => 'staff'];
        @endphp
        @foreach($audiences as $label => $val)
        @php
          $aActive = ($activeAudience === $label || ($label === 'All' && !request('audience')));
        @endphp
        <a href="{{ route('notices') }}?{{ http_build_query(array_filter(['priority' => request('priority'), 'audience' => $val])) }}"
           class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all"
           style="{{ $aActive ? 'background:#c4973a;color:#1a0a00;border:2px solid #c4973a' : 'background:transparent;color:#374151;border:2px solid #d1d5db' }}">
          {{ $label }}
        </a>
        @endforeach
      </div>

      @if(request('priority') || request('audience'))
      <a href="{{ route('notices') }}"
         class="px-4 py-1.5 rounded-full text-xs font-medium transition-all ml-auto"
         style="background:rgba(107,45,57,0.08);color:#6b2d39;border:2px solid transparent">
        &#10005; Clear filters
      </a>
      @endif

    </div>
  </div>
</section>

<section class="py-12 md:py-16" style="background-color:#f5f5f4;">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    @if(isset($notices) && $notices->isNotEmpty())

      <p class="text-sm text-stone-500 mb-6">
        Showing <strong>{{ $notices->count() }}</strong> of <strong>{{ $notices->total() }}</strong> notices
      </p>

      <div class="space-y-4" x-data>
        @foreach($notices as $notice)
        @php
          $priority = strtolower($notice->priority ?? 'normal');
          $audience = strtolower($notice->audience ?? 'everyone');
          $borderColor = match($priority) { 'urgent' => '#dc2626', 'high' => '#d97706', default => '#6b2d39' };
          $isExpired = !empty($notice->valid_until) && \Carbon\Carbon::parse($notice->valid_until)->isPast();
        @endphp
        <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4" style="border-left-color:{{ $borderColor }};">
          <div class="p-6">
            @if(!empty($notice->attachment))
            @php
              $attachmentUrl = str_starts_with($notice->attachment, 'assets/')
                ? asset($notice->attachment)
                : \Illuminate\Support\Facades\Storage::disk('public')->url($notice->attachment);
              $attachmentExt = strtolower(pathinfo($notice->attachment, PATHINFO_EXTENSION));
              $isImageAttachment = in_array($attachmentExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            @endphp
            <div class="mb-4">
              @if($isImageAttachment)
                <img src="{{ $attachmentUrl }}" alt="{{ $notice->title }}" class="h-48 w-full rounded-lg object-cover border border-stone-200/80" />
              @else
                <a href="{{ $attachmentUrl }}" target="_blank" class="inline-flex items-center gap-2 rounded-md border border-stone-300 bg-stone-50 px-4 py-2 text-sm font-medium text-brand hover:bg-stone-100">
                  View Attachment
                </a>
              @endif
            </div>
            @endif
            <div class="flex flex-wrap items-center gap-2 mb-3">
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
              <span class="px-2.5 py-1 rounded-full text-xs font-bold" style="background:rgba(107,45,57,0.1);color:#6b2d39">Normal</span>
              @endif

              @php
                $audColor = match($audience) { 'students' => 'bg-blue-100 text-blue-800', 'staff' => 'bg-green-100 text-green-800', default => 'bg-gray-100 text-gray-600' };
              @endphp
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $audColor }}">
                {{ ucfirst($audience) }}
              </span>

              @if($isExpired)
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-400">Expired</span>
              @endif

              @if(!empty($notice->published_at))
              <span class="ml-auto text-xs text-stone-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ \Carbon\Carbon::parse($notice->published_at)->format('d M Y') }}
              </span>
              @endif
            </div>

            <h3 class="font-bold text-lg mb-2 leading-snug" style="color:#6b2d39">{{ $notice->title }}</h3>

            @if(!empty($notice->content))
            <div x-data="{ expanded: false }">
              <div x-show="!expanded" class="text-sm text-stone-600 leading-relaxed">
                {{ Str::limit($notice->content, 150) }}
              </div>
              <div x-show="expanded" class="text-sm text-stone-600 leading-relaxed" style="display:none">
                {{ $notice->content }}
              </div>

              <div class="flex items-center justify-between mt-4 pt-3 border-t border-stone-100">
                @if(!empty($notice->valid_until))
                <span class="text-xs flex items-center gap-1 {{ $isExpired ? 'text-stone-400' : '' }}"
                      style="{{ !$isExpired ? 'color:#c4973a' : '' }}">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ $isExpired ? 'Expired' : 'Valid until' }}:
                  {{ \Carbon\Carbon::parse($notice->valid_until)->format('d M Y') }}
                </span>
                @else
                <span></span>
                @endif

                @if(strlen($notice->content) > 150)
                <button @click="expanded = !expanded"
                        class="inline-flex items-center gap-1 text-xs font-semibold transition-colors"
                        style="color:#6b2d39">
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
          @if($priority === 'urgent')
          <div class="h-1 w-full" style="background:linear-gradient(to right,#dc2626,#fca5a5,#dc2626)"></div>
          @endif
        </div>
        @endforeach
      </div>
    @elseif(request('priority') || request('audience'))
    <div class="text-center py-20 rounded-xl border border-stone-200/80 bg-white shadow-md">
      <div class="text-4xl mb-3">🔍</div>
      <h3 class="text-2xl font-bold mb-2" style="color:#6b2d39">No Notices Match Your Filters</h3>
      <p class="text-stone-500 mb-6">Try adjusting the Priority or Audience filter above.</p>
      <a href="{{ route('notices') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md text-sm font-semibold text-white transition hover:opacity-90"
         style="background:#6b2d39">View All Notices</a>
    </div>
    @else
    <div class="text-center py-20 rounded-xl border border-stone-200/80 bg-white shadow-md">
      <div class="text-4xl mb-3">📋</div>
      <h3 class="text-2xl font-bold mb-2" style="color:#6b2d39">No Notices Published Yet</h3>
      <p class="text-stone-500">Official notices and announcements will appear here once published by the administration.</p>
    </div>
    @endif

  </div>
</section>

@if(isset($notices) && $notices->hasPages())
<section class="py-8 bg-white">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm p-4 border border-stone-200/80">
      {{ $notices->appends(request()->query())->links() }}
    </div>
  </div>
</section>
@endif

<section class="bg-brand py-12 text-white md:py-14">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <div class="rounded-xl px-8 py-10" style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.12)">
      <h3 class="text-2xl font-semibold text-white mb-2">For Urgent Matters</h3>
      <p class="mb-8 text-white/90">
        Contact the college administration directly for time-sensitive enquiries.
      </p>
      <div class="flex flex-wrap justify-center gap-4">
        @if(!empty($college->phone))
        <a href="tel:{{ $college->phone }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md font-semibold text-white transition hover:bg-white/10"
           style="border:2px solid rgba(255,255,255,0.5)">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
          {{ $college->phone }}
        </a>
        @endif
        @if(!empty($college->email))
        <a href="mailto:{{ $college->email }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md font-semibold transition hover:opacity-90"
           style="background:#c4973a;color:#1a0a00">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Email Us
        </a>
        @endif
      </div>
    </div>
  </div>
</section>

@endsection
