<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — Teacher Portal | JDCA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    [x-cloak] { display: none !important; }
    .sidebar-w { width: 280px; }
    .main-pl { padding-left: 0; }
    @media (min-width: 1024px) { .main-pl { padding-left: 280px; } }
  </style>
</head>
<body class="portal-theme font-sans antialiased min-h-screen" x-data="{ sidebarOpen: false }">

<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/60 z-30 lg:hidden"></div>

<aside class="sidebar-w portal-shell-sidebar fixed top-0 left-0 bottom-0 flex flex-col z-40 transition-transform duration-300 ease-in-out"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
  <div class="flex items-center gap-3 px-5 py-5 flex-shrink-0" style="border-bottom: 1px solid rgba(255,255,255,0.08)">
    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #c4973a">
      <span class="text-white font-black text-[11px] leading-none">JDCA</span>
    </div>
    <div>
      <div class="text-white font-bold text-sm leading-tight">Teacher Portal</div>
      <div class="text-xs mt-0.5" style="color: rgba(255,255,255,0.35)">Jinnah Degree College</div>
    </div>
  </div>

  <div class="mx-3 mt-3 mb-1 rounded-xl px-3.5 py-3 flex items-center gap-3 flex-shrink-0"
       style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08)">
    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-white text-sm"
         style="background: #c4973a">
      {{ strtoupper(substr(auth('teacher')->user()->name, 0, 1)) }}
    </div>
    <div class="min-w-0 flex-1">
      <div class="text-white text-sm font-semibold truncate leading-snug">{{ auth('teacher')->user()->name }}</div>
      <div class="text-xs truncate mt-0.5" style="color: rgba(255,255,255,0.4)">{{ auth('teacher')->user()->employee_id }}</div>
    </div>
  </div>

  <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">
    @php
      $navLinks = [
        ['route' => 'teacher.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'teacher.timetable', 'label' => 'Timetable', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['route' => 'teacher.attendance', 'label' => 'Attendance', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['route' => 'teacher.materials', 'label' => 'Materials', 'icon' => 'M12 6.253v11.494m-9-5.747h18'],
        ['route' => 'teacher.assignments', 'label' => 'Assignments', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['route' => 'teacher.notices', 'label' => 'Notices', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        ['route' => 'teacher.profile', 'label' => 'My Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
      ];
    @endphp

    <p class="px-3 pb-1 text-[10px] font-semibold uppercase tracking-widest" style="color: rgba(255,255,255,0.25)">Navigation</p>

    @foreach($navLinks as $link)
      @php $active = request()->routeIs($link['route']); @endphp
      <a href="{{ route($link['route']) }}" @click="sidebarOpen = false"
         class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150"
         style="{{ $active ? 'background: rgba(255,255,255,0.12); color: #fff;' : 'color: rgba(255,255,255,0.55);' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0" style="{{ $active ? 'color: #c4973a' : 'color: rgba(255,255,255,0.4)' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $link['icon'] }}"/>
        </svg>
        <span class="flex-1">{{ $link['label'] }}</span>
      </a>
    @endforeach
  </nav>

  <div class="p-3 flex-shrink-0" style="border-top: 1px solid rgba(255,255,255,0.08)">
    <a href="{{ route('home') }}" target="_blank"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all mb-1"
       style="color: rgba(255,255,255,0.4)">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
      </svg>
      College Website
    </a>
    <form action="{{ route('teacher.logout') }}" method="POST">
      @csrf
      <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all text-left"
              style="color: rgba(255,255,255,0.4)">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
        </svg>
        Sign Out
      </button>
    </form>
  </div>
</aside>

<div class="main-pl min-h-screen flex flex-col">
  <header class="sticky top-0 z-20 portal-topbar flex-shrink-0">
    <div class="flex items-center justify-between px-4 sm:px-6 h-16">
      <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-400 hover:bg-white/5 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <div>
          <h1 class="font-semibold text-slate-50" style="font-size:15px">@yield('title', 'Dashboard')</h1>
          <p class="text-xs text-slate-400 hidden sm:block">
            {{ auth('teacher')->user()->designation ?? 'Teacher' }}
            &bull; {{ auth('teacher')->user()->department?->name ?? 'Department not assigned' }}
          </p>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
          <div class="text-xs font-medium text-slate-300">{{ now()->format('l') }}</div>
          <div class="text-[11px] text-slate-500">{{ now()->format('d M Y') }}</div>
        </div>
        <a href="{{ route('teacher.profile') }}"
           class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-sm"
           style="background: #17324f" title="My Profile">
          {{ strtoupper(substr(auth('teacher')->user()->name, 0, 1)) }}
        </a>
      </div>
    </div>
  </header>

  @if(session('success'))
    <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium text-emerald-200" style="background: rgba(22,163,74,0.14); border: 1px solid rgba(34,197,94,0.22)">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium text-rose-200" style="background: rgba(239,68,68,0.14); border: 1px solid rgba(248,113,113,0.22)">
      {{ session('error') }}
    </div>
  @endif

  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
      @yield('content')
    </div>
  </main>

  <footer class="portal-footer text-xs text-slate-500 text-center py-3 px-6 flex-shrink-0">
    &copy; {{ date('Y') }} Jinnah School &amp; Degree College Astore &mdash; Teacher Portal
  </footer>
</div>

</body>
</html>
