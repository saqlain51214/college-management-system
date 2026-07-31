@extends('layouts.public')
@section('title', 'Home — ' . ($college->college_name ?? 'JDCA'))

@section('content')
    <div style="padding-top: var(--site-header-offset, 6rem);">
        {{-- Modern, clean layout --}}
        @include('components.home.hero')
        @include('components.home.quick-tiles')
        @include('components.home.features')       {{-- stats band, moved up below the slider --}}
        @if($homeSections['elevate-learning']['is_active'] ?? true)
            @include('components.home.elevate')
        @endif
        @include('components.home.programs')
        @if($homeSections['campus-life']['is_active'] ?? true)
            @include('components.home.student-life')
        @endif
        @include('components.home.message-desk')
        @if($homeSections['testimonials']['is_active'] ?? true)
            @include('components.home.testimonials')
        @endif
        @include('components.home.news')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
@endpush
