@extends('layouts.public')
@section('title', 'College Gallery — ' . ($college->college_name ?? 'JDCA'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/gallery.css') }}">
@endpush

@section('content')

    <main id="main" tabindex="-1" class="site-main outline-none [&_h1]:font-display [&_h2]:font-display [&_h3]:font-sans [&_h4]:font-sans [&_h1]:tracking-tight [&_h2]:tracking-tight [&_h3]:tracking-tight [&_h4]:tracking-tight">
        <section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1562774053-701939374585.jpg') }}')] bg-cover bg-center opacity-25"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
            <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
                <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                    <span class="mx-2 text-white/40" aria-hidden="true">/</span>
                    <span class="text-white">College Gallery</span>
                </nav>
                <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'College gallery' }}</h1>
                <p class="mt-3 max-w-2xl text-sm text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? 'Campus, labs, student life, and events-explore our college through photography.' }}</p>
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

        <section class="relative border-b border-stone-200/80 bg-[#faf8f7] py-10 md:py-14" aria-labelledby="gallery-intro">
            <div class="pointer-events-none absolute inset-0 opacity-[0.35] gallery-hero-shine" aria-hidden="true"></div>
            <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
                <p id="gallery-intro" class="max-w-3xl font-display text-xl font-medium leading-snug text-ink sm:text-2xl md:text-3xl">
                    A living album of <span class="text-brand">{{ $college->college_name ?? 'JDCA' }}</span>—where classrooms, courts, and ceremonies come together.
                </p>
                <p class="mt-4 max-w-2xl text-sm leading-relaxed text-stone-600 sm:text-base">Tap any image to view it full size. Use the filters to browse by theme.</p>
                <div class="mt-8 flex flex-wrap gap-4 border-t border-stone-200/80 pt-8 text-center sm:text-left">
                    <div class="min-w-[6rem] flex-1 sm:flex-none">
                        <p class="font-display text-2xl font-semibold text-brand sm:text-3xl">40+</p>
                        <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Moments</p>
                    </div>
                    <div class="hidden h-10 w-px bg-stone-200 sm:block" aria-hidden="true"></div>
                    <div class="min-w-[6rem] flex-1 sm:flex-none">
                        <p class="font-display text-2xl font-semibold text-brand sm:text-3xl">4</p>
                        <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Themes</p>
                    </div>
                    <div class="hidden h-10 w-px bg-stone-200 sm:block" aria-hidden="true"></div>
                    <div class="min-w-[6rem] flex-1 sm:flex-none">
                        <p class="font-display text-2xl font-semibold text-brand sm:text-3xl">100%</p>
                        <p class="text-xs font-medium uppercase tracking-wider text-stone-500">On-campus</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="sticky top-24 z-30 border-b border-stone-200/90 bg-white/95 py-4 backdrop-blur-md md:top-28" aria-label="Gallery filters">
            <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Browse</p>
                <div class="flex flex-wrap gap-2" role="group" aria-label="Filter gallery by category">
                    <button type="button" data-gallery-filter="all" class="gallery-filter rounded-full border border-brand bg-brand px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-brand-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">All</button>
                    <button type="button" data-gallery-filter="campus" class="gallery-filter rounded-full border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 transition hover:border-brand/40 hover:text-brand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">Campus</button>
                    <button type="button" data-gallery-filter="labs" class="gallery-filter rounded-full border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 transition hover:border-brand/40 hover:text-brand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">Labs &amp; learning</button>
                    <button type="button" data-gallery-filter="sports" class="gallery-filter rounded-full border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 transition hover:border-brand/40 hover:text-brand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">Sports &amp; life</button>
                    <button type="button" data-gallery-filter="events" class="gallery-filter rounded-full border border-stone-200 bg-white px-4 py-2 text-xs font-semibold text-stone-700 transition hover:border-brand/40 hover:text-brand focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">Events</button>
                </div>
            </div>
        </section>

        <section class="bg-gradient-to-b from-white via-surface to-white py-12 md:py-20" aria-labelledby="gallery-grid-heading">
            <h2 id="gallery-grid-heading" class="sr-only">Photo grid</h2>
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <div id="galleryGrid" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($galleryImages as $image)
                        <button type="button" class="gallery-card is-active group relative min-h-[260px] overflow-hidden rounded-2xl border border-stone-200/80 bg-stone-100 shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                            data-cats="{{ $image['category'] ?? 'campus' }}"
                            aria-label="Open image: {{ $image['title'] }}">
                            <img src="{{ $image['src'] }}" alt="{{ $image['title'] }}"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                loading="lazy" decoding="async"
                                data-caption="{{ $image['desc'] ?: $image['title'] }}">
                            <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink/85 via-ink/10 to-transparent opacity-90"></span>
                            <span class="absolute bottom-0 left-0 right-0 p-4 text-left text-white">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-white/80">{{ ucfirst($image['category'] ?? 'campus') }}</span>
                                <span class="mt-1 block font-display text-lg font-semibold">{{ $image['title'] }}</span>
                                @if(!empty($image['desc']))
                                    <span class="mt-1 block text-xs text-white/80">{{ $image['desc'] }}</span>
                                @endif
                            </span>
                        </button>
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-stone-300 bg-white p-8 text-center text-sm text-stone-500">
                            No gallery images are available yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <dialog id="galleryLightbox" aria-labelledby="lightbox-caption" aria-modal="true">
            <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-ink shadow-2xl shadow-black/50">
                <button type="button" id="galleryLightboxClose" class="absolute right-3 top-3 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md transition hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white" aria-label="Close image">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img id="galleryLightboxImg" src="" alt="" class="max-h-[min(78vh,720px)] w-full object-contain bg-black/40">
                <p id="lightbox-caption" class="border-t border-white/10 bg-ink/90 px-4 py-3 text-sm text-white/95"></p>
            </div>
            <form method="dialog" class="mt-4 flex justify-center">
                <button type="submit" class="rounded-full border border-white/30 px-5 py-2 text-sm font-semibold text-white transition hover:bg-white/10">Close</button>
            </form>
        </dialog>

    </main>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/gallery-page.js') }}" defer></script>
@endpush
