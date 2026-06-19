@php
    $newsSection = $pageContent['news'] ?? [];
@endphp

<section class="border-b border-stone-200/50 bg-white py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        <div class="mb-10 text-center md:mb-12">
            <h2 class="mb-3 font-display text-2xl font-semibold tracking-tight text-ink sm:text-3xl md:text-[2rem]">{{ $newsSection['section_title'] ?? 'Latest news' }}</h2>
            <div class="mx-auto mb-4 flex justify-center" aria-hidden="true">
                <div class="h-1 w-16 rounded-full bg-gradient-to-r from-brand to-brand-dark sm:w-20"></div>
            </div>
            <p class="mx-auto max-w-2xl text-sm leading-relaxed text-stone-600 md:text-base">
                {{ $newsSection['section_text'] ?? 'Updates from campus, academics, and student life.' }}
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
            @forelse($news->take(4) as $article)
                <div class="group rounded-xl border border-slate-200/60 bg-white/95 p-4 shadow-sm backdrop-blur-sm transition hover:border-slate-300/80">
                    <img src="{{ $article->featured_image ? \Illuminate\Support\Facades\Storage::url($article->featured_image) : asset('assets/images/photo-1552664730-d307ca884978.jpg') }}" alt="{{ $article->title }}"
                        class="mb-3 h-44 w-full rounded-lg object-cover shadow-md shadow-stone-900/[0.1] sm:mb-4 sm:h-48"
                        loading="lazy" decoding="async" />

                    <p class="text-sm text-brand mb-2">
                        {{ optional($article->published_date)->format('D, F d') }} <span class="text-stone-500">/ {{ $article->category ?? 'News' }}</span>
                    </p>

                    <h3 class="mb-2 text-left text-base font-semibold text-neutral-900 transition group-hover:text-brand md:text-lg">
                        {{ $article->title }}
                    </h3>

                    <p class="mb-4 text-left text-sm leading-relaxed text-stone-600">
                        {{ \Illuminate\Support\Str::limit($article->excerpt ?? strip_tags($article->content ?? ''), 95) }}
                    </p>

                    <a href="{{ route('news.show', $article->slug) }}" class="text-brand font-medium text-sm flex items-center gap-1">
                        Read More →
                    </a>
                </div>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-stone-300 bg-white p-8 text-center text-sm text-stone-500">
                    No news articles are published yet.
                </div>
            @endforelse

        </div>

    </div>
</section>
