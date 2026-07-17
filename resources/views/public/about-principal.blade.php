@extends('layouts.public')
@section('title', 'Message from the Principal')
@section('content')
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
        <aside class="lg:col-span-1">
            <div class="rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden text-center">
                <div class="site-brand-gradient py-6 px-4">
                    <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center text-3xl font-black text-white shadow-lg" style="background:rgba(255,255,255,0.2)">
                        {{ strtoupper(substr($college->principal ?? 'A', 0, 1)) }}
                    </div>
                </div>
                <div class="p-5">
                    <h2 class="font-bold text-stone-800 text-lg">{{ $college->principal ?? 'Arif Ali' }}</h2>
                    <p class="text-sm text-stone-500 mt-0.5">Principal, JDCA</p>
                    <p class="text-sm text-stone-500">{{ $college->city ?? 'Astore' }}, Gilgit-Baltistan</p>
                </div>
            </div>
        </aside>
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-stone-800 font-display mb-6">Dear Students &amp; Community,</h2>
            <div class="prose prose-stone max-w-none text-stone-700 leading-relaxed space-y-5">
                <p>Assalam-o-Alaikum and welcome to Jinnah School &amp; Degree College Astore. As Principal, I am honoured to lead an institution that has become a beacon of hope and opportunity for the youth of Astore district.</p>
                <p>We believe that education is not merely about degrees — it is about character, service, and the ability to contribute meaningfully to society. Our mission is to nurture well-rounded graduates who carry the values of integrity, hard work, and compassion into everything they do.</p>
                <p>At JDCA, our dedicated faculty is committed to your success. We continuously improve our teaching methods, facilities, and student support systems to ensure that every student receives the attention and guidance they deserve.</p>
                <p>To parents: we are your partners in this journey. Your trust motivates us to maintain the highest standards of education and discipline.</p>
                <p>To students: embrace every opportunity. The world awaits your contribution — and JDCA is here to prepare you for it.</p>
            </div>
            <div class="mt-8 pt-6 border-t border-stone-200">
                <p class="font-bold text-stone-800 text-lg">{{ $college->principal ?? 'Arif Ali' }}</p>
                <p class="text-stone-500 text-sm">Principal, {{ $college->college_name ?? 'JDCA' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
