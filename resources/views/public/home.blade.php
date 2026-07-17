@extends('layouts.public')
@section('title', 'Home — ' . ($college->college_name ?? 'JDCA'))

@section('content')
    <div style="padding-top: var(--site-header-offset, 6rem);">
        @include('components.home.hero')
        @include('components.home.features')
        @include('components.home.programs')
        @include('components.home.news')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
@endpush
