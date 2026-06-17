@extends('layouts.public')
@section('title', 'News & Updates — JDCA')
@section('meta_description', 'Stay informed with the latest news, events, announcements and academic updates from Jinnah School & Degree College Astore (JDCA), Gilgit-Baltistan.')

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
      <span style="color:rgba(255,255,255,.80);">News &amp; Updates</span>
    </nav>
    <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">
      News &amp; <span style="color:var(--c-gold);">Updates</span>
    </h1>
    <p class="mt-3 max-w-2xl text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,.80);">
      Stay informed with the latest announcements, events, and academic updates from {{ $college->college_name ?? 'Jinnah Degree College Astore' }}.
    </p>
  </div>
</section>

{{-- ============================================================
     SECTION 2: FEATURED ARTICLE
     ============================================================ --}}
@if(isset($featured) && $featured)
<section class="py-16 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    <div class="flex items-center gap-3 mb-8">
      <span class="w-3 h-3 rounded-full" style="background:var(--c-gold)"></span>
      <h2 class="text-xl font-extrabold" style="color:var(--c-primary)">Featured Story</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 rounded-2xl overflow-hidden shadow-xl">

      {{-- Left panel --}}
      <div class="relative min-h-64 flex items-end p-8" style="background:linear-gradient(135deg,#5a1212,#1e293b)">
        <div class="absolute inset-0 pointer-events-none opacity-10"
             style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px"></div>

        {{-- Category badge top-left --}}
        <div class="absolute top-6 left-6">
          <span class="px-3 py-1 rounded-full text-xs font-bold text-white"
                style="background:rgba(255,255,255,.2)">{{ $featured->category ?? 'News' }}</span>
        </div>

        {{-- Views top-right --}}
        @if(!empty($featured->views))
        <div class="absolute top-6 right-6 flex items-center gap-1 text-xs" style="color:rgba(255,255,255,.6)">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          {{ number_format($featured->views) }}
        </div>
        @endif

        {{-- JDCA watermark --}}
        <div class="absolute bottom-6 right-6 text-5xl font-extrabold" style="color:rgba(255,255,255,.06)">JDCA</div>
      </div>

      {{-- Right panel --}}
      <div class="bg-white p-8 flex flex-col justify-center">

        <div class="flex items-center gap-3 mb-4">
          <span class="flex items-center gap-1.5 text-xs text-gray-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ $featured->published_at ? \Carbon\Carbon::parse($featured->published_at)->format('d M Y') : '' }}
          </span>
          <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white"
                style="background:var(--c-primary)">{{ $featured->category ?? 'News' }}</span>
        </div>

        <h2 class="text-2xl font-extrabold mb-3 leading-snug" style="color:var(--c-primary)">{{ $featured->title }}</h2>

        @if(!empty($featured->excerpt))
        <p class="text-gray-600 leading-relaxed mb-6">{{ Str::limit($featured->excerpt, 180) }}</p>
        @endif

        <div class="flex items-center justify-between">
          <a href="{{ route('news.show', $featured->slug ?? $featured->id) }}"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-white text-sm transition-transform hover:-translate-y-0.5"
             style="background:var(--c-primary)">
            Read Full Article
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
          </a>
          <span class="text-xs font-bold px-3 py-1 rounded-full"
                style="background:rgba(196,151,58,.12);color:var(--c-gold)">Featured Story</span>
        </div>

      </div>
    </div>
  </div>
</section>
@endif

{{-- ============================================================
     SECTION 3: CATEGORY FILTER
     ============================================================ --}}
<div class="sticky z-30 bg-white shadow-sm border-b border-gray-100" style="top:0">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4">
    <div class="flex items-center gap-2 flex-wrap">

      @php
        $cats = ['All', 'News', 'Events', 'Announcements', 'Academic'];
        $activeCat = request('category', 'All');
      @endphp

      @foreach($cats as $cat)
      @php
        $isActive = ($cat === 'All' && $activeCat === 'All') || ($cat !== 'All' && $activeCat === $cat);
      @endphp
      <a href="{{ route('news') }}{{ $cat !== 'All' ? '?category=' . urlencode($cat) : '' }}"
         class="px-5 py-2 rounded-full text-sm font-semibold transition-all"
         style="{{ $isActive ? 'background:var(--c-primary);color:#fff;border:2px solid var(--c-primary)' : 'background:transparent;color:#374151;border:2px solid #d1d5db' }}">
        {{ $cat }}
      </a>
      @endforeach

      @if($activeCat !== 'All')
      <a href="{{ route('news') }}"
         class="px-4 py-2 rounded-full text-sm font-medium transition-all"
         style="background:rgba(90,18,18,.08);color:var(--c-primary);border:2px solid transparent">
        &#10005; Clear filter
      </a>
      @endif

    </div>
  </div>
</div>

{{-- ============================================================
     SECTION 4: ARTICLES GRID
     ============================================================ --}}
<section class="py-16" style="background:var(--c-light)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">

    @php
      $catGradients = [
        'news'          => 'linear-gradient(135deg,#5a1212,#7b1a1a)',
        'events'        => 'linear-gradient(135deg,#1e3a5f,#1e4a8f)',
        'announcements' => 'linear-gradient(135deg,#92400e,#b45309)',
        'academic'      => 'linear-gradient(135deg,#065f46,#047857)',
      ];
    @endphp

    {{-- Results count --}}
    @if(isset($articles))
    <p class="text-sm text-gray-500 mb-6">
      Showing <strong>{{ $articles->count() }}</strong> of <strong>{{ $articles->total() }}</strong> articles
      @if($activeCat !== 'All') in <strong>{{ $activeCat }}</strong> @endif
    </p>
    @endif

    @if(isset($articles) && $articles->isNotEmpty())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($articles as $article)
      @php
        $catKey = strtolower($article->category ?? 'news');
        $artGradient = $catGradients[$catKey] ?? $catGradients['news'];
      @endphp
      <article class="bg-white rounded-2xl shadow-md overflow-hidden hover:-translate-y-1 transition-transform">

        {{-- Colored header --}}
        <div class="relative h-36 flex items-end px-5 pb-4" style="background:{{ $artGradient }}">
          <div class="absolute inset-0 pointer-events-none opacity-10"
               style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:22px 22px"></div>
          <div class="absolute top-4 left-5">
            <span class="px-2 py-0.5 rounded-full text-xs font-bold text-white"
                  style="background:rgba(255,255,255,.2)">{{ $article->category ?? 'News' }}</span>
          </div>
          {{-- JDCA watermark --}}
          <div class="absolute bottom-2 right-4 text-4xl font-extrabold" style="color:rgba(255,255,255,.07)">JDCA</div>
        </div>

        {{-- Card body --}}
        <div class="p-6">

          {{-- Date + category row --}}
          <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center gap-1 text-xs text-gray-400">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : 'Recent' }}
            </span>
            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                  style="background:rgba(90,18,18,.08);color:var(--c-primary)">{{ $article->category ?? 'News' }}</span>
          </div>

          {{-- Title --}}
          <h3 class="font-bold text-lg mb-2 leading-snug line-clamp-2 hover:underline cursor-pointer"
              style="color:var(--c-primary)">
            <a href="{{ route('news.show', $article->slug ?? $article->id) }}">{{ $article->title }}</a>
          </h3>

          {{-- Excerpt --}}
          @if(!empty($article->excerpt))
          <p class="text-sm text-gray-500 mb-4 leading-relaxed line-clamp-2">{{ $article->excerpt }}</p>
          @endif

          {{-- Footer --}}
          <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <a href="{{ route('news.show', $article->slug ?? $article->id) }}"
               class="inline-flex items-center gap-1 text-sm font-semibold transition-colors hover:underline"
               style="color:var(--c-primary)">
              Read More
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @if(!empty($article->views))
            <span class="flex items-center gap-1 text-xs text-gray-400">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              {{ number_format($article->views) }}
            </span>
            @endif
          </div>

        </div>
      </article>
      @endforeach
    </div>
    @else
    {{-- Empty state --}}
    <div class="text-center py-20">
      <div class="text-6xl mb-4">📰</div>
      <h3 class="text-2xl font-bold mb-2" style="color:var(--c-primary)">No Articles Found</h3>
      <p class="text-gray-500 mb-6">
        @if($activeCat !== 'All')
          No articles found in the <strong>{{ $activeCat }}</strong> category.
        @else
          Articles will appear here once they are published.
        @endif
      </p>
      @if($activeCat !== 'All')
      <a href="{{ route('news') }}"
         class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white"
         style="background:var(--c-primary)">View All Articles</a>
      @endif
    </div>
    @endif

  </div>
</section>

{{-- ============================================================
     SECTION 5: PAGINATION
     ============================================================ --}}
@if(isset($articles) && $articles->hasPages())
<section class="py-8 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="bg-white rounded-2xl shadow-sm p-4">
      {{ $articles->appends(request()->query())->links() }}
    </div>
  </div>
</section>
@endif

{{-- ============================================================
     SECTION 6: NEWSLETTER / STAY UPDATED CTA
     ============================================================ --}}
<section class="py-20" style="background:var(--c-primary)">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="relative rounded-3xl overflow-hidden px-8 py-12">

      {{-- Decorative circles --}}
      <div class="absolute top-0 right-0 w-64 h-64 rounded-full pointer-events-none" style="background:rgba(255,255,255,.05)"></div>
      <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full pointer-events-none" style="background:rgba(0,0,0,.1)"></div>

      <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        {{-- Left --}}
        <div>
          <div class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-semibold mb-6"
               style="background:rgba(196,151,58,.2);border:1px solid rgba(196,151,58,.35);color:#e8c46a">
            Stay Informed
          </div>
          <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Never Miss an Update</h2>
          <p class="mb-6" style="color:rgba(255,255,255,.75)">
            Follow our official channels and visit this page regularly to stay up to date with all news,
            events, academic announcements, and notices from {{ $college->short_name ?? 'JDCA' }}.
          </p>
          <ul class="space-y-3">
            @foreach(['Academic schedule & exam dates','Admissions opening announcements','Results & merit lists','College events & ceremonies'] as $bullet)
            <li class="flex items-center gap-3 text-sm" style="color:rgba(255,255,255,.85)">
              <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold" style="background:var(--c-gold);color:#1a0a00">&#10003;</span>
              {{ $bullet }}
            </li>
            @endforeach
          </ul>
        </div>

        {{-- Right: contact cards --}}
        <div class="space-y-4">
          @if(!empty($college->email))
          <div class="flex items-center gap-4 rounded-2xl p-5" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:var(--c-gold)">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <p class="text-xs font-bold mb-0.5" style="color:var(--c-gold)">Email Us</p>
              <a href="mailto:{{ $college->email }}" class="text-white text-sm font-medium hover:underline">{{ $college->email }}</a>
            </div>
          </div>
          @endif

          @if(!empty($college->phone))
          <div class="flex items-center gap-4 rounded-2xl p-5" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12)">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:var(--c-gold)">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <div>
              <p class="text-xs font-bold mb-0.5" style="color:var(--c-gold)">Call Us</p>
              <a href="tel:{{ $college->phone }}" class="text-white text-sm font-medium hover:underline">{{ $college->phone }}</a>
            </div>
          </div>
          @endif

          <a href="{{ route('contact') }}"
             class="flex items-center justify-center gap-2 w-full py-4 rounded-2xl font-bold text-sm transition-transform hover:-translate-y-0.5"
             style="background:var(--c-gold);color:#1a0a00">
            Get in Touch with Us
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

@endsection
