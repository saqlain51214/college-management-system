@extends('layouts.public')
@section('title', 'News & Updates — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523240795612-9a054b0db644.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">News & Updates</span>
    </nav>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'News & Updates' }}</h1>
    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/90 sm:text-base">
      {{ $pageContent['intro_text'] ?? ('Stay informed with the latest announcements, events, and academic updates from ' . ($college->college_name ?? 'Jinnah Degree College') . '.') }}
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

<section class="border-b border-stone-200/80 bg-white py-12 md:py-16">
  <div class="mx-auto max-w-6xl px-4 sm:px-6">

    @if(isset($featured) && $featured)
    <div class="mb-10">
      <div class="flex items-center gap-3 mb-6">
        <span class="w-3 h-3 rounded-full" style="background:#c4973a"></span>
        <h2 class="text-xl font-semibold text-ink">Featured Story</h2>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 rounded-xl overflow-hidden shadow-lg border border-stone-200/80">
        <div class="relative min-h-48 sm:min-h-64 flex items-end p-4 sm:p-8" style="background:linear-gradient(135deg,#6b2d39,#5a2430)">
          @if(!empty($featured->featured_image))
          @php
            $featuredImage = str_starts_with($featured->featured_image, 'assets/')
              ? asset($featured->featured_image)
              : \Illuminate\Support\Facades\Storage::disk('public')->url($featured->featured_image);
          @endphp
          <img src="{{ $featuredImage }}" alt="{{ $featured->title }}" class="absolute inset-0 h-full w-full object-cover" />
          <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/25 to-ink/20"></div>
          @endif
          <div class="absolute inset-0 pointer-events-none opacity-10" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:24px 24px"></div>
          <div class="absolute top-6 left-6">
            <span class="px-3 py-1 rounded-full text-xs font-bold text-white" style="background:rgba(255,255,255,.2)">{{ $featured->category ?? 'News' }}</span>
          </div>
          @if(!empty($featured->views))
          <div class="absolute top-6 right-6 flex items-center gap-1 text-xs" style="color:rgba(255,255,255,.6)">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            {{ number_format($featured->views) }}
          </div>
          @endif
        </div>
        <div class="bg-white p-5 sm:p-8 flex flex-col justify-center">
          <div class="flex items-center gap-3 mb-4">
            <span class="flex items-center gap-1.5 text-xs text-stone-500">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              {{ $featured->published_date ? \Carbon\Carbon::parse($featured->published_date)->format('d M Y') : '' }}
            </span>
            <span class="text-xs px-2 py-0.5 rounded-full font-semibold text-white" style="background:#6b2d39">{{ $featured->category ?? 'News' }}</span>
          </div>
          <h2 class="text-2xl font-semibold mb-3 leading-snug text-ink">{{ $featured->title }}</h2>
          @if(!empty($featured->excerpt))
          <p class="text-stone-600 leading-relaxed mb-6">{{ Str::limit($featured->excerpt, 180) }}</p>
          @endif
          <div class="flex items-center justify-between">
            <a href="{{ route('news.show', $featured->slug ?? $featured->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md text-sm font-semibold text-white transition hover:bg-brand-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand" style="background:#6b2d39">
              Read Full Article
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <span class="text-xs font-bold px-3 py-1 rounded-full" style="background:rgba(196,151,58,.12);color:#c4973a">Featured Story</span>
          </div>
        </div>
      </div>
    </div>
    @endif

    <div class="flex items-center gap-2 flex-wrap mb-8">
      @php
        $cats = ['All', 'News', 'Events', 'Announcements', 'Academic'];
        $activeCat = request('category', 'All');
      @endphp
      @foreach($cats as $cat)
      @php
        $isActive = ($cat === 'All' && $activeCat === 'All') || ($cat !== 'All' && $activeCat === $cat);
      @endphp
      <a href="{{ route('news') }}{{ $cat !== 'All' ? '?category=' . urlencode($cat) : '' }}" class="px-5 py-2 rounded-full text-sm font-semibold transition-all" style="{{ $isActive ? 'background:#6b2d39;color:#fff;border:2px solid #6b2d39' : 'background:transparent;color:#374151;border:2px solid #d1d5db' }}">
        {{ $cat }}
      </a>
      @endforeach
      @if($activeCat !== 'All')
      <a href="{{ route('news') }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all" style="background:rgba(107,45,57,.08);color:#6b2d39;border:2px solid transparent">
        &#10005; Clear filter
      </a>
      @endif
    </div>

    @if(isset($articles) && $articles->isNotEmpty())
    <p class="text-sm text-stone-600 mb-6">Showing <strong>{{ $articles->count() }}</strong> of <strong>{{ $articles->total() }}</strong> articles @if($activeCat !== 'All') in <strong>{{ $activeCat }}</strong> @endif</p>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($articles as $article)
      <article class="rounded-xl border border-stone-200/80 bg-white overflow-hidden shadow-md hover:shadow-lg transition">
        <div class="relative h-36 flex items-end px-5 pb-4" style="background:linear-gradient(135deg,#6b2d39,#5a2430)">
          @if(!empty($article->featured_image))
          @php
            $articleImage = str_starts_with($article->featured_image, 'assets/')
              ? asset($article->featured_image)
              : \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image);
          @endphp
          <img src="{{ $articleImage }}" alt="{{ $article->title }}" class="absolute inset-0 h-full w-full object-cover" />
          <div class="absolute inset-0 bg-gradient-to-t from-ink/80 via-ink/25 to-ink/20"></div>
          @endif
          <div class="absolute inset-0 pointer-events-none opacity-10" style="background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:22px 22px"></div>
          <div class="absolute top-4 left-5">
            <span class="px-2 py-0.5 rounded-full text-xs font-bold text-white" style="background:rgba(255,255,255,.2)">{{ $article->category ?? 'News' }}</span>
          </div>
        </div>
        <div class="p-6">
          <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center gap-1 text-xs text-stone-500">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              {{ $article->published_date ? \Carbon\Carbon::parse($article->published_date)->format('d M Y') : 'Recent' }}
            </span>
          </div>
          <h3 class="font-semibold text-ink text-lg mb-2 leading-snug line-clamp-2 hover:underline cursor-pointer">
            <a href="{{ route('news.show', $article->slug ?? $article->id) }}">{{ $article->title }}</a>
          </h3>
          @if(!empty($article->excerpt))
          <p class="text-sm text-stone-600 mb-4 leading-relaxed line-clamp-2">{{ $article->excerpt }}</p>
          @endif
          <div class="flex items-center justify-between pt-4 border-t border-stone-100">
            <a href="{{ route('news.show', $article->slug ?? $article->id) }}" class="inline-flex items-center gap-1 text-sm font-semibold transition-colors hover:underline text-brand">
              Read More
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            @if(!empty($article->views))
            <span class="flex items-center gap-1 text-xs text-stone-500">
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
    <div class="text-center py-20 rounded-xl border border-stone-200/80 bg-white shadow-md">
      <div class="text-6xl mb-4">📰</div>
      <h3 class="text-2xl font-semibold mb-2 text-brand">No Articles Found</h3>
      <p class="text-stone-600 mb-6">
        @if($activeCat !== 'All')
        No articles found in the <strong>{{ $activeCat }}</strong> category.
        @else
        Articles will appear here once they are published.
        @endif
      </p>
      @if($activeCat !== 'All')
      <a href="{{ route('news') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md text-sm font-semibold text-white transition hover:bg-brand-dark" style="background:#6b2d39">View All Articles</a>
      @endif
    </div>
    @endif

    @if(isset($articles) && $articles->hasPages())
    <div class="mt-10 pt-8 border-t border-stone-200/80">
      {{ $articles->appends(request()->query())->links() }}
    </div>
    @endif

  </div>
</section>

<section class="bg-brand py-12 text-center text-white md:py-14">
  <div class="mx-auto max-w-2xl px-4 sm:px-6">
    <h2 class="font-display text-2xl font-semibold sm:text-3xl">Never Miss an Update</h2>
    <p class="mt-3 text-sm text-white/90 sm:text-base">
      Follow our official channels and visit this page regularly to stay up to date with all news, events, and announcements.
    </p>
    <div class="mt-6 flex flex-wrap gap-3 justify-center">
      <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-md bg-white px-5 py-2.5 text-sm font-semibold text-brand shadow-lg transition hover:bg-stone-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">Contact Us</a>
      <a href="{{ route('news') }}" class="inline-flex items-center justify-center rounded-md border-2 border-white px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">View All News</a>
    </div>
  </div>
</section>

@endsection
