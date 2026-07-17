@php $newsSection = $pageContent['news'] ?? []; @endphp

<section class="py-12 sm:py-16 lg:py-24" style="background:var(--site-body-bg)" aria-labelledby="news-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        {{-- Header --}}
        <div class="mb-8 sm:mb-12 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-[0.16em]" style="color:var(--site-gold)">College News</p>
                <h2 id="news-heading" class="font-display text-2xl font-bold text-stone-900 sm:text-3xl lg:text-4xl">
                    {{ $newsSection['section_title'] ?? 'Latest News' }}
                </h2>
            </div>
            <a href="{{ route('news') }}"
               class="shrink-0 self-start sm:self-auto text-sm font-semibold transition hover:opacity-75"
               style="color:var(--site-brand)">All news →</a>
        </div>

        @php $articles = $news->take(4); $featured = $articles->first(); $rest = $articles->skip(1); @endphp

        @if($featured)
        <div class="grid gap-4 sm:gap-6 lg:grid-cols-5">

            {{-- Featured article --}}
            <a href="{{ route('news.show', $featured->slug) }}"
               class="group flex flex-col overflow-hidden rounded-2xl bg-stone-900 lg:col-span-3">
                <div class="relative h-44 sm:h-64 lg:h-72 overflow-hidden">
                    <img src="{{ $featured->featured_image ? \Illuminate\Support\Facades\Storage::url($featured->featured_image) : asset('assets/images/photo-1552664730-d307ca884978.jpg') }}"
                         alt="{{ $featured->title }}"
                         class="h-full w-full object-cover opacity-75 transition duration-500 group-hover:scale-105 group-hover:opacity-85"
                         loading="lazy" decoding="async">
                </div>
                <div class="flex flex-1 flex-col justify-end p-5 sm:p-7">
                    <div class="mb-2 sm:mb-3 flex flex-wrap items-center gap-2 text-xs text-white/50">
                        <span class="rounded-full px-2.5 py-0.5 font-semibold uppercase tracking-wide text-white"
                              style="background:var(--site-brand); font-size:10px">{{ $featured->category ?? 'News' }}</span>
                        @if($featured->published_date)
                        <time>{{ $featured->published_date->format('d M Y') }}</time>
                        @endif
                    </div>
                    <h3 class="mb-1.5 sm:mb-2 font-display text-lg sm:text-xl lg:text-2xl font-bold leading-snug text-white group-hover:text-white/90">
                        {{ $featured->title }}
                    </h3>
                    <p class="text-xs sm:text-sm leading-relaxed text-white/60 line-clamp-2">
                        {{ \Illuminate\Support\Str::limit($featured->excerpt ?? strip_tags($featured->content ?? ''), 130) }}
                    </p>
                    <span class="mt-3 sm:mt-4 inline-flex items-center gap-1 text-xs font-semibold text-white/80 transition group-hover:gap-2">
                        Read article
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </a>

            {{-- Remaining articles --}}
            <div class="flex flex-col gap-3 sm:gap-4 lg:col-span-2">
                @forelse($rest as $article)
                <a href="{{ route('news.show', $article->slug) }}"
                   class="group flex gap-3 sm:gap-4 rounded-xl p-3 sm:p-4 ring-1 ring-stone-200/70 transition hover:ring-stone-300"
                   style="background:var(--site-surface)">
                    {{-- Thumbnail --}}
                    <div class="h-16 w-20 sm:h-20 sm:w-24 shrink-0 overflow-hidden rounded-lg">
                        <img src="{{ $article->featured_image ? \Illuminate\Support\Facades\Storage::url($article->featured_image) : asset('assets/images/photo-1552664730-d307ca884978.jpg') }}"
                             alt="{{ $article->title }}"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                             loading="lazy" decoding="async">
                    </div>
                    {{-- Text --}}
                    <div class="min-w-0 flex-1">
                        <p class="mb-0.5 text-[10px] font-bold uppercase tracking-wide text-stone-400">
                            {{ optional($article->published_date)->format('d M Y') }}
                            @if($article->category) · {{ $article->category }} @endif
                        </p>
                        <h3 class="font-display text-sm font-bold leading-snug text-stone-900 transition group-hover:text-[var(--site-brand)] line-clamp-2">
                            {{ $article->title }}
                        </h3>
                        <p class="mt-0.5 text-xs leading-relaxed text-stone-500 line-clamp-1 sm:line-clamp-2">
                            {{ \Illuminate\Support\Str::limit($article->excerpt ?? strip_tags($article->content ?? ''), 80) }}
                        </p>
                    </div>
                </a>
                @empty
                <p class="text-sm text-stone-400 py-4">No additional articles.</p>
                @endforelse
            </div>

        </div>
        @else
        <div class="rounded-2xl border border-dashed border-stone-200 py-12 sm:py-16 text-center">
            <p class="text-sm text-stone-400">No news articles published yet.</p>
        </div>
        @endif

    </div>
</section>
