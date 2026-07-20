@extends('layouts.public')
@section('title', 'Home — ' . ($college->college_name ?? 'JDCA'))

@section('content')
    <div style="padding-top: var(--site-header-offset, 6rem);">
        {{-- Modern, clean layout --}}
        @include('components.home.hero')
        @include('components.home.quick-tiles')
        @include('components.home.features')       {{-- stats band, moved up below the slider --}}
        @include('components.home.programs')
        @include('components.home.message-desk')
        @include('components.home.news')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
@endpush
