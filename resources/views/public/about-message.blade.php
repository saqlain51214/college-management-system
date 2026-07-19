@extends('layouts.public')
@section('title', "Message from the Principal")

@section('content')
@php $customBody = !empty($cmsPage) ? $cmsPage->customBodyHtml() : null; @endphp
@if($customBody)
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1"><a href="{{ route('home') }}" class="hover:text-white">Home</a><span class="mx-1.5">›</span><span class="text-white/90">{{ $cmsPage->title }}</span></p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $cmsPage->title }}</h1>
        </div>
    </div>
</div>
<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12"><div class="prose-content max-w-none">{!! $customBody !!}</div></div>
@else
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('about') }}" class="hover:text-white">About Us</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Message from Principal</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Message from the Principal</h1>
        </div>
    </div>
</div>

<div class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
    <div class="grid lg:grid-cols-3 gap-10 items-start">

        {{-- Principal Card --}}
        <aside class="lg:col-span-1">
            <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden text-center">
                <div class="site-brand-gradient py-6 px-4">
                    <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-3xl font-black text-white shadow-lg" style="background:rgba(255,255,255,0.2)">
                        {{ strtoupper(substr($college->principal ?? 'A', 0, 1)) }}
                    </div>
                </div>
                <div class="p-5">
                    <h2 class="font-bold text-stone-800 text-lg">{{ $college->principal ?? 'Principal' }}</h2>
                    <p class="text-sm text-stone-500 mt-0.5">Principal</p>
                    <p class="text-sm text-stone-500">{{ $college->name }}</p>
                    <div class="mt-4 pt-4 border-t border-stone-100 space-y-2 text-sm text-stone-500 text-left">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $college->city }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $college->email }}" class="hover:underline break-all">{{ $college->email }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Message Content --}}
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-stone-800 font-display mb-6">Dear Students, Faculty &amp; Stakeholders,</h2>

            <div class="prose prose-stone max-w-none text-stone-700 leading-relaxed space-y-5">
                <p>
                    It is with great pride and a sense of deep responsibility that I welcome you to Jinnah Degree College Astore. This institution was founded on the belief that every student, regardless of their geographic or socioeconomic circumstances, deserves access to quality education.
                </p>
                <p>
                    The Astore Valley, with its remarkable natural beauty and resilient people, has always valued knowledge as the key to progress. At JDCA, we are committed to honoring that tradition by providing an environment where students can grow intellectually, morally, and professionally.
                </p>
                <p>
                    Our dedicated faculty brings both academic expertise and a passion for teaching. Our programs — affiliated with the Karakoram International University — are designed to meet national standards while being sensitive to the unique needs and aspirations of our students from Gilgit-Baltistan.
                </p>
                <p>
                    I encourage every student to make the most of the opportunities available here. Come with curiosity, leave with capability. Engage with your teachers, participate in campus life, and remember that your success is the success of your community.
                </p>
                <p>
                    To our parents and community members: thank you for entrusting us with your children's futures. We take that responsibility seriously and remain committed to transparency, excellence, and continuous improvement.
                </p>
                <p>
                    Together, let us build a brighter future for Astore, for Gilgit-Baltistan, and for Pakistan.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-stone-200">
                <p class="font-bold text-stone-800 text-lg">{{ $college->principal ?? 'Principal' }}</p>
                <p class="text-stone-500 text-sm">Principal, {{ $college->name }}</p>
                <p class="text-stone-400 text-sm">{{ $college->city }}</p>
            </div>
        </div>

    </div>
</div>
@endif
@endsection
