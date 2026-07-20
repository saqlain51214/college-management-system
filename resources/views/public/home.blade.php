@extends('layouts.public')
@section('title', 'Home — ' . ($college->college_name ?? 'JDCA'))

@section('content')
    <div style="padding-top: var(--site-header-offset, 6rem);">
        @include('components.home.hero')
        @include('components.home.features')
        @include('components.home.message-desk')
        @include('components.home.about')
        @include('components.home.programs')
        @include('components.home.elevate')
        @include('components.home.student-life')
        @include('components.home.news')
        @include('components.home.events')
        @include('components.home.testimonials')
        @include('components.home.affiliations')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
@endpush
