@extends('layouts.public')
@section('title', 'Home — ' . ($college->college_name ?? 'JDCA'))

@section('content')
    <div style="padding-top: var(--site-header-offset, 6rem);">
        @include('components.home.hero')
        @include('components.home.features')
        @include('components.home.announcement')
        @if($homeSections['elevate-learning']['is_active'] ?? true)
            @include('components.home.elevate')
        @endif
        @if($homeSections['campus-life']['is_active'] ?? true)
            @include('components.home.student-life')
        @endif
        @if($homeSections['testimonials']['is_active'] ?? true)
            @include('components.home.testimonials')
        @endif
        @include('components.home.about')
        @include('components.home.programs')
        @include('components.home.news')
        @include('components.home.events')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
@endpush
