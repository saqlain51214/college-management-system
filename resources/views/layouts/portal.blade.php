<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — Student Portal | JDCA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    [x-cloak] { display: none !important; }
    .sidebar-w { width: 260px; }
    .main-pl { padding-left: 0; }
    @media (min-width: 1024px) { .main-pl { padding-left: 260px; } }
  </style>
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: false }">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/60 z-30 lg:hidden"
     x-transition:enter="transition-opacity ease-out duration-200"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>

{{-- ──────────── SIDEBAR ──────────── --}}
<aside class="sidebar-w fixed top-0 left-0 bottom-0 flex flex-col z-40 transition-transform duration-300 ease-in-out"
       style="background: #1e3a5f;"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

  {{-- Logo --}}
  <div class="flex items-center gap-3 px-5 py-5 flex-shrink-0" style="border-bottom: 1px solid rgba(255,255,255,0.08)">
    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #c4973a">
      <span class="text-white font-black text-[11px] leading-none">JDCA</span>
    </div>
    <div>
      <div class="text-white font-bold text-sm leading-tight">Student Portal</div>
      <div class="text-xs mt-0.5" style="color: rgba(255,255,255,0.35)">Jinnah Degree College</div>
    </div>
  </div>

  {{-- Student info card --}}
  <div class="mx-3 mt-3 mb-1 rounded-xl px-3.5 py-3 flex items-center gap-3 flex-shrink-0"
       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08)">
    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-white text-sm"
         style="background: #c4973a">
      {{ strtoupper(substr(auth('student')->user()->name, 0, 1)) }}
    </div>
    <div class="min-w-0 flex-1">
      <div class="text-white text-sm font-semibold truncate leading-snug">{{ auth('student')->user()->name }}</div>
      <div class="text-xs truncate mt-0.5" style="color: rgba(255,255,255,0.4)">{{ auth('student')->user()->roll_number }}</div>
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">
    @php
    $navLinks = [
      ['route' => 'portal.dashboard', 'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
      ['route' => 'portal.results',   'label' => 'Exam Results', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
      ['route' => 'portal.fees',      'label' => 'Fee Status',   'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
      ['route' => 'portal.timetable', 'label' => 'Timetable',    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
      ['route' => 'portal.notices',   'label' => 'Notices',      'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
      ['route' => 'portal.profile',   'label' => 'My Profile',   'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    ];
    @endphp

    <p class="px-3 pb-1 text-[10px] font-semibold uppercase tracking-widest" style="color: rgba(255,255,255,0.25)">Navigation</p>

    @foreach($navLinks as $link)
    @php $active = request()->routeIs($link['route']); @endphp
    <a href="{{ route($link['route']) }}" @click="sidebarOpen = false"
       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
       style="{{ $active ? 'background: rgba(255,255,255,0.12); color: #fff;' : 'color: rgba(255,255,255,0.55);' }}"
       onmouseover="{{ !$active ? "this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'" : '' }}"
       onmouseout="{{ !$active ? "this.style.background='transparent';this.style.color='rgba(255,255,255,0.55)'" : '' }}">
      <svg class="w-[18px] h-[18px] flex-shrink-0" style="{{ $active ? 'color: #c4973a' : 'color: rgba(255,255,255,0.4)' }}"
           fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $link['icon'] }}"/>
      </svg>
      <span class="flex-1">{{ $link['label'] }}</span>
      @if($active)<span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#c4973a"></span>@endif
    </a>
    @endforeach
  </nav>

  {{-- Bottom actions --}}
  <div class="p-3 flex-shrink-0" style="border-top: 1px solid rgba(255,255,255,0.08)">
    <a href="{{ route('home') }}" target="_blank"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all mb-1"
       style="color: rgba(255,255,255,0.4)"
       onmouseover="this.style.background='rgba(255,255,255,0.07)';this.style.color='#fff'"
       onmouseout="this.style.background='transparent';this.style.color='rgba(255,255,255,0.4)'">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
      </svg>
      College Website
    </a>
    <form action="{{ route('portal.logout') }}" method="POST">
      @csrf
      <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all text-left"
              style="color: rgba(255,255,255,0.4)"
              onmouseover="this.style.background='rgba(239,68,68,0.15)';this.style.color='#fca5a5'"
              onmouseout="this.style.background='transparent';this.style.color='rgba(255,255,255,0.4)'">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        Sign Out
      </button>
    </form>
  </div>
</aside>

{{-- ──────────── MAIN WRAPPER ──────────── --}}
<div class="main-pl min-h-screen flex flex-col">

  {{-- Topbar --}}
  <header class="sticky top-0 z-20 bg-white flex-shrink-0" style="border-bottom: 1px solid #e5e7eb">
    <div class="flex items-center justify-between px-4 sm:px-6 h-16">
      {{-- Hamburger + Title --}}
      <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <div>
          <h1 class="font-semibold text-gray-800" style="font-size:15px">@yield('title', 'Dashboard')</h1>
          <p class="text-xs text-gray-400 hidden sm:block">
            {{ auth('student')->user()->academicProgram?->name ?? 'Program' }}
            &bull; Semester {{ auth('student')->user()->current_semester ?? '—' }}
          </p>
        </div>
      </div>
      {{-- Date + Avatar --}}
      <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
          <div class="text-xs font-medium text-gray-600">{{ now()->format('l') }}</div>
          <div class="text-[11px] text-gray-400">{{ now()->format('d M Y') }}</div>
        </div>
        <a href="{{ route('portal.profile') }}"
           class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-sm"
           style="background: #1e3a5f" title="My Profile">
          {{ strtoupper(substr(auth('student')->user()->name, 0, 1)) }}
        </a>
      </div>
    </div>
  </header>

  {{-- Flash messages --}}
  @if(session('success'))
  <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium text-green-800"
       style="background: #f0fdf4; border: 1px solid #bbf7d0">
    {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium text-red-800"
       style="background: #fef2f2; border: 1px solid #fecaca">
    {{ session('error') }}
  </div>
  @endif

  {{-- Page content --}}
  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    @yield('content')
  </main>

  <footer class="bg-white text-xs text-gray-400 text-center py-3 px-6 flex-shrink-0"
          style="border-top: 1px solid #f3f4f6">
    &copy; {{ date('Y') }} Jinnah School &amp; Degree College Astore &mdash; Student Portal
  </footer>
</div>

</body>
</html>
