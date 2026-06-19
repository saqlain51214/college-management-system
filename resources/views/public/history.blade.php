@extends('layouts.public')
@section('title', 'History & Location — ' . ($college->college_name ?? 'JDCA'))

@section('content')

    <main id="main" tabindex="-1" class="site-main outline-none [&_h1]:font-display [&_h2]:font-display [&_h3]:font-sans [&_h4]:font-sans [&_h1]:tracking-tight [&_h2]:tracking-tight [&_h3]:tracking-tight [&_h4]:tracking-tight">
        <section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14" aria-labelledby="page-title">
            <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1562774053-701939374585.jpg') }}')] bg-cover bg-center opacity-25"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
            <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
                <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                    <span class="mx-2 text-white/40" aria-hidden="true">/</span>
                    <a href="{{ route('about') }}" class="transition hover:text-white">About Us</a>
                    <span class="mx-2 text-white/40" aria-hidden="true">/</span>
                    <span class="text-white">History &amp; Location</span>
                </nav>
                <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'History & Location' }}</h1>
                <p class="mt-3 max-w-2xl text-sm text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? ("Institutional history, campus location in " . ($college->city ?? 'Astore') . ", and how we connect with the region's education landscape.") }}</p>
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
            <div class="mx-auto max-w-4xl space-y-4 px-4 sm:px-6">
                <h2 class="font-display text-xl font-semibold text-ink">History</h2>
                <p class="text-sm leading-relaxed text-stone-600">{{ $college->college_name ?? 'Jinnah School & Degree College Astore' }} was established to meet the growing demand for disciplined, board-focused education in {{ $college->city ?? 'Astore' }}. Over the years the college expanded laboratories, digital learning support, and co-curricular programmes while keeping student welfare and academic integrity at the centre.</p>
                <p class="text-sm leading-relaxed text-stone-600">Founded in <strong class="text-ink">{{ $college->established_year ?? 2010 }}</strong>, the college is proudly affiliated with <strong class="text-ink">{{ $college->affiliation ?? 'Karakoram International University' }}</strong>, providing recognized intermediate and undergraduate streams.</p>
            </div>
        </section>

        <section class="bg-surface py-12 md:py-16">
            <div class="mx-auto max-w-4xl space-y-4 px-4 sm:px-6">
                <h2 class="font-display text-xl font-semibold text-ink">Location &amp; Access</h2>
                <p class="text-sm leading-relaxed text-stone-600">The main campus is located at <strong class="text-ink">{{ $college->address ?? 'Distt. Astore Village Eidgah, Near Ali Murtaza Chowk, Astore, GB' }}</strong>, with road links to major residential areas.</p>
                <p class="text-sm leading-relaxed text-stone-600">For inquiries or to schedule a visit, please contact us at <a href="tel:{{ str_replace(' ', '', $college->phone ?? '') }}" class="text-brand hover:underline">{{ $college->phone ?? '' }}</a>.</p>
            </div>
        </section>
    </main>

@endsection
