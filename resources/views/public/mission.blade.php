@extends('layouts.public')
@section('title', 'Mission & Vision — ' . ($college->college_name ?? 'JDCA'))

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
                    <span class="text-white">Mission &amp; Vision</span>
                </nav>
                <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Mission & Vision' }}</h1>
                <p class="mt-3 max-w-2xl text-sm text-white/90 sm:text-base">{{ $pageContent['intro_text'] ?? 'Formal mission, vision, and graduate attributes aligned with national goals and educational quality themes.' }}</p>
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
                <h2 class="font-display text-xl font-semibold text-ink">Mission</h2>
                <p class="text-sm leading-relaxed text-stone-600">To provide affordable, rigorous intermediate and undergraduate-level instruction that develops ethical citizens, strong board and university entrance outcomes, and respect for Pakistan’s languages, cultures, and constitutional values.</p>
                
                <h2 class="mt-8 font-display text-xl font-semibold text-ink">Vision</h2>
                <p class="text-sm leading-relaxed text-stone-600">To be a college of first choice for families seeking transparent admissions, qualified faculty, safe campuses, and pathways into engineering, medicine, business, ICT, and social sciences across Pakistan and abroad.</p>
            </div>
        </section>

        <section class="bg-surface py-12 md:py-16">
            <div class="mx-auto max-w-4xl space-y-4 px-4 sm:px-6">
                <h2 class="font-display text-xl font-semibold text-ink">Graduate attributes</h2>
                <ul class="list-inside list-disc space-y-2">
                    <li class="text-sm leading-relaxed text-stone-600">Disciplinary knowledge aligned with board / university partner syllabi</li>
                    <li class="text-sm leading-relaxed text-stone-600">Digital literacy and responsible use of study tools</li>
                    <li class="text-sm leading-relaxed text-stone-600">Communication skills in English and Urdu as appropriate</li>
                    <li class="text-sm leading-relaxed text-stone-600">Understanding of Pakistan Studies, ethics, and community service</li>
                </ul>
            </div>
        </section>
    </main>

@endsection
