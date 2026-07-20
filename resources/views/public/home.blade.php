@extends('layouts.public')
@section('title', 'Home — ' . ($college->college_name ?? 'JDCA'))

@section('content')
    <div style="padding-top: var(--site-header-offset, 6rem);">
        {{-- Clean, KIU-style layout: hero → quick tiles → news → programmes → message desk → stats --}}
        @include('components.home.hero')
        @include('components.home.quick-tiles')
        @include('components.home.news')
        @include('components.home.programs')
        @include('components.home.message-desk')
        @include('components.home.features')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
@endpush
