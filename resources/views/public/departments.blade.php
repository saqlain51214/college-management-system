@extends('layouts.public')
@section('title', 'Departments')

@section('content')
<div class="pt-[var(--site-header-offset)]">
    <div class="site-brand-gradient py-8 px-4 sm:px-6">
        <div class="mx-auto max-w-6xl">
            <p class="text-xs text-white/60 mb-1">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-1.5">›</span>
                <span class="text-white/90">Departments</span>
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Academic Departments</h1>
            <p class="text-white/70 text-sm mt-1">Explore our departments and their programs</p>
        </div>
    </div>
</div>

<div class="mx-auto max-w-6xl px-4 sm:px-6 py-12">
    @if($departments->isEmpty())
        <div class="rounded-2xl border border-dashed border-stone-300 p-16 text-center">
            <svg class="w-14 h-14 mx-auto text-stone-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <p class="text-stone-500 font-medium text-lg">Departments are being set up.</p>
            <p class="text-stone-400 mt-1">Please check back soon.</p>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($departments as $dept)
            <a href="{{ route('departments.show', $dept->slug) }}"
               class="group rounded-2xl border border-stone-200 bg-white shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                @if($dept->banner_image_url)
                    <div class="h-36 overflow-hidden">
                        <img src="{{ $dept->banner_image_url }}" alt="{{ $dept->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @else
                    <div class="h-36 site-brand-gradient flex items-center justify-center">
                        <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                @endif
                <div class="p-5">
                    <h2 class="font-bold text-stone-800 group-hover:site-brand-text transition-colors text-base leading-snug">{{ $dept->name }}</h2>
                    @if($dept->hod_name)
                        <p class="text-xs text-stone-500 mt-1">HOD: {{ $dept->hod_name }}</p>
                    @endif
                    @if($dept->description)
                        <p class="text-sm text-stone-600 mt-2 line-clamp-2">{{ Str::limit(strip_tags($dept->description), 100) }}</p>
                    @endif
                    <div class="mt-4 flex items-center text-xs font-semibold" style="color:var(--site-brand)">
                        View Department
                        <svg class="w-3.5 h-3.5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
