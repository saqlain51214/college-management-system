@extends('layouts.public')
@section('title', $article->title)
@section('content')
<section class="bg-navy py-20 pt-32">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-xs text-white/50 mb-3"><a href="{{ route('home') }}" class="hover:text-white">Home</a> / <a href="{{ route('news') }}" class="hover:text-white">News</a> / {{ Str::limit($article->title, 40) }}</div>
    @if($article->category)<div class="text-gold text-sm font-semibold mb-2">{{ ucfirst($article->category) }}</div>@endif
    <h1 class="text-3xl lg:text-4xl font-bold text-white leading-snug">{{ $article->title }}</h1>
    <div class="flex items-center gap-4 mt-4 text-white/50 text-sm">
      <span>{{ $article->published_date ? \Carbon\Carbon::parse($article->published_date)->format('d M Y') : '' }}</span>
      <span>{{ number_format($article->views) }} views</span>
    </div>
  </div>
</section>
<section class="py-16 bg-white">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    @if($article->featured_image)
    <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->title }}" class="w-full rounded-2xl mb-10 max-h-96 object-cover">
    @endif
    <div class="prose-content text-gray-700 leading-relaxed">
      {!! $article->content !!}
    </div>
    <div class="mt-12 pt-8 border-t border-gray-100">
      <a href="{{ route('news') }}" class="text-navy font-semibold text-sm hover:text-gold transition">← Back to News</a>
    </div>
    @if($related->count())
    <div class="mt-12">
      <h3 class="text-xl font-bold text-navy mb-6">Related Articles</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($related as $r)
        <a href="{{ route('news.show', $r->slug) }}" class="group block bg-gray-50 rounded-xl p-4 hover:bg-navy/5 transition">
          <div class="font-semibold text-gray-800 group-hover:text-navy text-sm line-clamp-2">{{ $r->title }}</div>
          <div class="text-xs text-gray-400 mt-1">{{ $r->published_date ? \Carbon\Carbon::parse($r->published_date)->format('d M Y') : '' }}</div>
        </a>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
