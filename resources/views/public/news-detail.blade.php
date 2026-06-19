@extends('layouts.public')
@section('title', $article->title . ' — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523240795612-9a054b0db644.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <a href="{{ route('news') }}" class="transition hover:text-white">News & Updates</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">{{ Str::limit($article->title, 40) }}</span>
    </nav>
    @if($article->category)
    <div class="text-brand text-sm font-semibold mb-2">
      <span class="px-3 py-1 rounded-full bg-brand/20 border border-brand/30">{{ ucfirst($article->category) }}</span>
    </div>
    @endif
    <h1 id="page-title" class="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight">{{ $article->title }}</h1>
    <div class="flex items-center gap-4 mt-4 text-white/70 text-sm">
      <span>{{ $article->published_date ? \Carbon\Carbon::parse($article->published_date)->format('d M Y') : '' }}</span>
      @if($article->views)
      <span>{{ number_format($article->views) }} views</span>
      @endif
    </div>
  </div>
</section>

<section class="py-12 md:py-16">
  <div class="mx-auto max-w-4xl px-4 sm:px-6">

    @if($article->featured_image)
    <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->title }}" class="w-full rounded-xl mb-10 max-h-96 object-cover border border-stone-200/80 shadow-md">
    @endif

    <div class="text-stone-700 leading-relaxed">
      {!! $article->content !!}
    </div>

    <div class="mt-12 pt-8 border-t border-stone-200/80">
      <a href="{{ route('news') }}" class="text-brand font-semibold text-sm hover:underline transition">← Back to News & Updates</a>
    </div>

    @if(isset($related) && $related->count())
    <div class="mt-12">
      <h3 class="text-xl font-semibold text-ink mb-6">Related Articles</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($related as $r)
        <a href="{{ route('news.show', $r->slug) }}" class="group block bg-white rounded-xl border border-stone-200/80 p-4 hover:shadow-lg transition">
          <div class="font-semibold text-ink text-sm line-clamp-2 group-hover:text-brand">{{ $r->title }}</div>
          <div class="text-xs text-stone-500 mt-1">{{ $r->published_date ? \Carbon\Carbon::parse($r->published_date)->format('d M Y') : '' }}</div>
        </a>
        @endforeach
      </div>
    </div>
    @endif

  </div>
</section>

<section class="bg-brand py-12 text-center text-white md:py-14">
  <div class="mx-auto max-w-2xl px-4 sm:px-6">
    <h2 class="font-display text-2xl font-semibold sm:text-3xl">More News & Updates</h2>
    <p class="mt-3 text-sm text-white/90 sm:text-base">
      Check out more latest news and updates from {{ $college->college_name ?? 'Jinnah School & Degree College' }}.
    </p>
    <div class="mt-6">
      <a href="{{ route('news') }}" class="inline-flex items-center justify-center rounded-md bg-white px-5 py-2.5 text-sm font-semibold text-brand shadow-lg transition hover:bg-stone-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-brand">
        View All News
      </a>
    </div>
  </div>
</section>

@endsection