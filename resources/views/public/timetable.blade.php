@extends('layouts.public')
@section('title', 'Class Timetable — ' . ($college->college_name ?? 'JDCA'))

@section('content')

<style>
  @media print {
    .no-print { display: none !important; }
    .print-only { display: block !important; }
    body { font-size: 11px; }
    .timetable-wrapper { box-shadow: none !important; border: 1px solid #ccc !important; }
    th, td { padding: 4px 6px !important; font-size: 10px !important; }
  }
  @media screen {
    .print-only { display: none; }
  }
  .timetable-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
  .slot-card {
    border-radius: 8px;
    padding: 6px 8px;
    color: #fff;
    font-size: 11.5px;
    line-height: 1.35;
    min-width: 90px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.18);
  }
  .slot-card .course-code {
    font-weight: 700;
    font-size: 10px;
    opacity: 0.85;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }
  .slot-card .course-name {
    font-weight: 600;
    margin-top: 1px;
    word-break: break-word;
  }
  .slot-card .slot-meta {
    font-size: 10px;
    opacity: 0.8;
    margin-top: 3px;
  }
  .slot-card .slot-teacher { margin-top: 2px; font-size: 10px; opacity: 0.85; }
  .empty-cell {
    border: 1.5px dashed #d1d5db;
    border-radius: 6px;
    height: 60px;
    background: #fafafa;
  }
  .time-cell {
    background: #f3f4f6;
    font-size: 11px;
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
    border-right: 2px solid #e5e7eb;
    min-width: 80px;
    padding: 8px 10px;
  }
  .tt-table { border-collapse: separate; border-spacing: 0; width: 100%; min-width: 700px; }
  .tt-table th, .tt-table td {
    border-bottom: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
    vertical-align: top;
    padding: 6px 7px;
  }
  .tt-table th:last-child, .tt-table td:last-child { border-right: none; }
  .tt-table tr:last-child td { border-bottom: none; }
</style>

<section class="relative overflow-hidden bg-ink pt-28 pb-12 text-white sm:pt-32 sm:pb-14 no-print" aria-labelledby="page-title">
  <div class="absolute inset-0 bg-[url('{{ asset('assets/images/photo-1523240795612-9a054b0db644.jpg') }}')] bg-cover bg-center opacity-20"></div>
  <div class="absolute inset-0 bg-gradient-to-br from-brand/90 via-ink/95 to-ink"></div>
  <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <nav class="mb-4 text-xs text-white/70" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
      <span class="mx-2 text-white/40">/</span>
      <span class="text-white">Timetable</span>
    </nav>
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-4" style="background:rgba(196,151,58,0.18); color:#c4973a; border:1px solid rgba(196,151,58,0.35)">
      <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
      Academic Schedule
    </div>
    <h1 id="page-title" class="font-display text-3xl font-semibold tracking-tight sm:text-4xl md:text-5xl">{{ $pageContent['intro_title'] ?? 'Class Timetable' }}</h1>
    <p class="mt-3 max-w-xl text-sm leading-relaxed text-white/90 sm:text-base">
      {{ $pageContent['intro_text'] ?? 'Weekly schedule for' }} <span class="font-semibold text-white">{{ $selectedProgram->name ?? 'all programs' }}</span>
      @if($selectedSemester) — Semester <span class="font-semibold text-white">{{ $selectedSemester }}</span> @endif
    </p>
    @if($selectedProgram && $selectedSemester && isset($slots) && $slots->isNotEmpty())
    <div class="mt-6">
      <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md font-semibold text-sm transition hover:opacity-90" style="background:#c4973a; color:#fff">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print Timetable
      </button>
    </div>
    @endif
  </div>
</section>

@if(!empty($pageContent['body_html']))
<section class="border-b border-stone-200/80 bg-white py-10 md:py-12 no-print">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="prose prose-stone max-w-none">
      {!! $pageContent['body_html'] !!}
    </div>
  </div>
</section>
@endif

<section class="no-print sticky top-0 z-30 bg-white border-b border-stone-200/80 shadow-sm py-4">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <form method="GET" action="{{ route('timetable') }}" class="flex flex-wrap items-end gap-3">
      <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Program</label>
        <div class="relative">
          <select name="program_id" class="w-full appearance-none bg-stone-50 border border-stone-300 rounded-md px-4 py-2.5 pr-9 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:border-brand transition">
            <option value="">— Select Program —</option>
            @foreach($programs as $prog)
              <option value="{{ $prog->id }}" {{ (isset($selectedProgram) && $selectedProgram && $selectedProgram->id == $prog->id) ? 'selected' : '' }}>
                {{ $prog->short_name ?? $prog->name }}
              </option>
            @endforeach
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-stone-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </div>
      </div>

      <div class="w-[130px]">
        <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Semester</label>
        <div class="relative">
          <select name="semester" class="w-full appearance-none bg-stone-50 border border-stone-300 rounded-md px-4 py-2.5 pr-9 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:border-brand transition">
            <option value="">— All —</option>
            @for($s = 1; $s <= 8; $s++)
              <option value="{{ $s }}" {{ $selectedSemester == $s ? 'selected' : '' }}>
                Semester {{ $s }}
              </option>
            @endfor
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-stone-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </div>
      </div>

      <div class="w-[120px]">
        <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Section</label>
        <div class="relative">
          <select name="section" class="w-full appearance-none bg-stone-50 border border-stone-300 rounded-md px-4 py-2.5 pr-9 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:border-brand transition">
            <option value="">All</option>
            @foreach(['A','B','C'] as $sec)
              <option value="{{ $sec }}" {{ request('section') === $sec ? 'selected' : '' }}>
                Section {{ $sec }}
              </option>
            @endforeach
          </select>
          <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-stone-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </div>
      </div>

      <div>
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md font-semibold text-sm text-white transition hover:opacity-90 active:scale-95" style="background:#6b2d39">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
          View Timetable
        </button>
      </div>

      @if($selectedProgram || $selectedSemester)
      <div class="self-end">
        <a href="{{ route('timetable') }}" class="text-xs text-stone-500 hover:text-stone-700 transition underline underline-offset-2">
          Reset
        </a>
      </div>
      @endif

    </form>
  </div>
</section>

<section class="py-12 md:py-16" style="background-color:#f5f5f4;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(!$selectedProgram || !$selectedSemester)
    <div class="bg-white rounded-xl shadow-md p-12 text-center border border-stone-200/80">
      <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center" style="background:rgba(107,45,57,0.07)">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#6b2d39">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <h3 class="text-2xl font-semibold text-stone-800 mb-2">Select a Program & Semester</h3>
      <p class="text-stone-500 max-w-md mx-auto text-sm leading-relaxed">
        Use the filter controls above to choose your program and semester, then click
        <span class="font-semibold" style="color:#6b2d39">View Timetable</span>
        to see the weekly class schedule.
      </p>
      @if($programs && $programs->count())
      <div class="mt-8 flex flex-wrap justify-center gap-2">
        @foreach($programs->take(6) as $prog)
        <a href="{{ route('timetable', ['program_id' => $prog->id, 'semester' => 1]) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold border transition hover:text-white" style="border-color:#6b2d39; color:#6b2d39" onmouseover="this.style.backgroundColor='#6b2d39'" onmouseout="this.style.backgroundColor='transparent'">
          {{ $prog->short_name ?? $prog->name }}
        </a>
        @endforeach
      </div>
      @endif
    </div>

    @elseif($selectedProgram && $selectedSemester && isset($slots) && $slots->isEmpty())
    <div class="bg-white rounded-xl shadow-md p-12 text-center border border-stone-200/80">
      <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center bg-amber-50">
        <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"/></svg>
      </div>
      <h3 class="text-2xl font-semibold text-stone-800 mb-2">No Timetable Published Yet</h3>
      <p class="text-stone-500 max-w-md mx-auto text-sm leading-relaxed">
        The timetable for <strong>{{ $selectedProgram->name }}</strong> — Semester {{ $selectedSemester }}
        has not been published yet. Please check back later or contact the college office.
      </p>
      <div class="mt-6 flex flex-wrap justify-center gap-3">
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md font-semibold text-sm text-white transition hover:opacity-90" style="background:#6b2d39">
          Contact College
        </a>
        <a href="{{ route('timetable') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md font-semibold text-sm border-2 transition hover:bg-stone-50" style="border-color:#6b2d39; color:#6b2d39">
          Try Another Program
        </a>
      </div>
    </div>

    @else

    @php
      $timeRanges = [
        ['start' => '08:00', 'end' => '09:00', 'label' => '08:00', 'sub' => '09:00'],
        ['start' => '09:00', 'end' => '10:00', 'label' => '09:00', 'sub' => '10:00'],
        ['start' => '10:15', 'end' => '11:15', 'label' => '10:15', 'sub' => '11:15'],
        ['start' => '11:15', 'end' => '12:15', 'label' => '11:15', 'sub' => '12:15'],
        ['start' => '12:00', 'end' => '13:00', 'label' => '12:00', 'sub' => '13:00'],
        ['start' => '13:00', 'end' => '14:00', 'label' => '13:00', 'sub' => '14:00'],
        ['start' => '14:00', 'end' => '15:00', 'label' => '14:00', 'sub' => '15:00'],
      ];

      $colors = [
        '#6b2d39',
        '#1e3a5f',
        '#0f766e',
        '#6d28d9',
        '#b45309',
        '#166534',
        '#be123c',
        '#0369a1',
      ];
      $courseColors = [];
      $colorIdx = 0;

      $grid = [];
      foreach ($days as $day) {
        $grid[$day] = [];
        $daySlots = $slots->get($day, collect());
        foreach ($daySlots as $slot) {
          $st = substr($slot->start_time, 0, 5);
          $grid[$day][$st] = $slot;
          $courseKey = $slot->course_id ?? ($slot->course->code ?? $slot->course->name ?? 'X');
          if (!isset($courseColors[$courseKey])) {
            $courseColors[$courseKey] = $colors[$colorIdx % count($colors)];
            $colorIdx++;
          }
        }
      }

      $skipCell = [];
      foreach ($days as $day) {
        $skipCell[$day] = [];
      }

      $rowspan = [];
      foreach ($days as $day) {
        foreach ($timeRanges as $tIdx => $tr) {
          $rowspan[$day][$tIdx] = 1;
          if (isset($grid[$day][$tr['start']])) {
            $slot = $grid[$day][$tr['start']];
            $endStr = substr($slot->end_time, 0, 5);
            $span = 1;
            for ($nx = $tIdx + 1; $nx < count($timeRanges); $nx++) {
              $nxEnd = $timeRanges[$nx]['end'];
              $nxStart = $timeRanges[$nx]['start'];
              if (strtotime($endStr) > strtotime($nxStart)) {
                $span++;
                $skipCell[$day][$nx] = true;
              } else {
                break;
              }
            }
            $rowspan[$day][$tIdx] = $span;
          }
        }
      }

      $dayLabels = [
        'monday'    => 'Monday',
        'tuesday'   => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday'  => 'Thursday',
        'friday'    => 'Friday',
        'saturday'  => 'Saturday',
      ];
      $dayShort = [
        'monday'    => 'MON',
        'tuesday'   => 'TUE',
        'wednesday' => 'WED',
        'thursday'  => 'THU',
        'friday'    => 'FRI',
        'saturday'  => 'SAT',
      ];
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
      <div>
        <h3 class="text-xl font-semibold" style="color:#111827">
          {{ $selectedProgram->name }}
          @if($selectedProgram->short_name)
            <span class="text-sm font-medium text-stone-500">({{ $selectedProgram->short_name }})</span>
          @endif
        </h3>
        <p class="text-sm text-stone-600 mt-0.5">
          Semester {{ $selectedSemester }}
          @if(request('section') && request('section') !== 'all')
            — Section {{ request('section') }}
          @endif
          — Academic Year {{ date('Y') }}-{{ date('y') + 1 }}
        </p>
      </div>
      <div class="flex items-center gap-2 no-print">
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold text-white transition hover:opacity-90" style="background:#6b2d39">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
          Print
        </button>
      </div>
    </div>

    <div class="print-only mb-4 text-center">
      <p class="font-bold text-lg">{{ $college->college_name ?? 'Jinnah School & Degree College Astore' }}</p>
      <p class="text-sm text-stone-600">Class Timetable — {{ $selectedProgram->name }} — Semester {{ $selectedSemester }}</p>
      <p class="text-xs text-stone-400">Printed: {{ now()->format('d M Y') }}</p>
    </div>

    <div class="timetable-wrapper bg-white rounded-xl shadow-lg overflow-hidden border border-stone-200/80">
      <div class="timetable-scroll">
        <table class="tt-table">
          <thead>
            <tr>
              <th class="text-center text-xs font-bold uppercase tracking-wide text-white py-4 px-3" style="background:#6b2d39; border-right:1px solid rgba(255,255,255,0.2); min-width:80px">
                Time
              </th>
              @foreach($days as $day)
              <th class="text-center text-xs font-bold uppercase tracking-wide text-white py-4 px-3" style="background:#6b2d39; border-right:1px solid rgba(255,255,255,0.15); min-width:120px">
                <span class="hidden sm:inline">{{ $dayLabels[$day] ?? ucfirst($day) }}</span>
                <span class="sm:hidden">{{ $dayShort[$day] ?? strtoupper(substr($day,0,3)) }}</span>
              </th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($timeRanges as $tIdx => $tr)

            @if($tr['start'] === '13:00')
            <tr class="no-print" style="background: repeating-linear-gradient(45deg, #fef9ee, #fef9ee 4px, #fffbf2 4px, #fffbf2 12px)">
              <td class="time-cell text-xs py-2" style="background:#fef3c7; color:#92400e; font-size:10px; border-right:2px solid #e5e7eb">
                <div class="font-bold">12:30</div>
                <div style="font-size:9px; color:#b45309">Prayer Break</div>
              </td>
              @foreach($days as $bday)
              <td class="text-center py-1.5" style="border-bottom:1px solid #e5e7eb; border-right:1px solid #e5e7eb">
                @if($bday === 'friday')
                  <span class="text-xs font-semibold" style="color:#92400e">Jumu'ah Prayer</span>
                @else
                  <span class="text-xs text-stone-400">Lunch Break</span>
                @endif
              </td>
              @endforeach
            </tr>
            @endif

            <tr class="{{ $tIdx % 2 === 0 ? '' : '' }} hover:bg-blue-50/30 transition-colors duration-100">
              <td class="time-cell">
                <div class="font-bold text-sm" style="color:#6b2d39">{{ $tr['label'] }}</div>
                <div class="text-stone-400 mt-0.5" style="font-size:10px">{{ $tr['sub'] }}</div>
              </td>
              @foreach($days as $day)
                @if(isset($skipCell[$day][$tIdx]) && $skipCell[$day][$tIdx])
                @else
                @php
                  $hasSlot = isset($grid[$day][$tr['start']]);
                  $span = $hasSlot ? ($rowspan[$day][$tIdx] ?? 1) : 1;
                  $slot = $hasSlot ? $grid[$day][$tr['start']] : null;
                  $courseKey = null;
                  $cellColor = null;
                  if ($slot) {
                    $courseKey = $slot->course_id ?? ($slot->course->code ?? $slot->course->name ?? 'X');
                    $cellColor = $courseColors[$courseKey] ?? '#6b2d39';
                  }
                @endphp
                <td @if($span > 1) rowspan="{{ $span }}" @endif style="border-bottom:1px solid #e5e7eb; border-right:1px solid #e5e7eb; vertical-align: {{ $span > 1 ? 'middle' : 'top' }}; padding: 6px 7px">
                  @if($slot)
                  <div class="slot-card" style="background-color: {{ $cellColor }}">
                    @if(isset($slot->course) && $slot->course->code)
                    <div class="course-code">{{ $slot->course->code }}</div>
                    @endif
                    <div class="course-name">
                      @if(isset($slot->course))
                        {{ $slot->course->name ?? 'N/A' }}
                      @else
                        Unknown Course
                      @endif
                    </div>
                    @if(isset($slot->teacher) && $slot->teacher)
                    <div class="slot-teacher">
                      <svg style="display:inline;width:8px;height:8px;margin-right:2px;opacity:0.7" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                      </svg>
                      {{ $slot->teacher->name ?? '' }}
                    </div>
                    @endif
                    <div class="slot-meta flex items-center gap-2 mt-1">
                      @if($slot->room)
                      <span>
                        <svg style="display:inline;width:8px;height:8px;margin-right:1px;opacity:0.7" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                        </svg>
                        {{ $slot->room }}
                      </span>
                      @endif
                      @if(isset($slot->section) && $slot->section)
                      <span class="px-1.5 py-0.5 rounded text-white font-bold" style="background:rgba(255,255,255,0.2); font-size:9px">
                        Sec {{ $slot->section }}
                      </span>
                      @endif
                      @if($span > 1)
                      <span class="px-1.5 py-0.5 rounded text-white font-bold" style="background:rgba(255,255,255,0.2); font-size:9px">
                        Lab
                      </span>
                      @endif
                    </div>
                  </div>
                  @else
                  <div class="empty-cell"></div>
                  @endif
                </td>
                @endif
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    @if(count($courseColors) > 0)
    <div class="mt-6 bg-white rounded-xl shadow-md p-5 no-print border border-stone-200/80">
      <h4 class="text-xs font-bold uppercase tracking-wide text-stone-500 mb-3">Course Legend</h4>
      <div class="flex flex-wrap gap-2">
        @foreach($courseColors as $courseKey => $color)
        @php
          $legendSlot = null;
          foreach ($days as $d) {
            foreach ($slots->get($d, collect()) as $s) {
              $ck = $s->course_id ?? ($s->course->code ?? $s->course->name ?? 'X');
              if ($ck == $courseKey) { $legendSlot = $s; break 2; }
            }
          }
        @endphp
        @if($legendSlot)
        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg" style="background:{{ $color }}10; border:1px solid {{ $color }}30">
          <span class="inline-block w-3 h-3 rounded-sm flex-shrink-0" style="background:{{ $color }}"></span>
          <span class="text-xs font-medium text-stone-700">
            @if(isset($legendSlot->course))
              {{ $legendSlot->course->code ? $legendSlot->course->code.' — ' : '' }}{{ $legendSlot->course->name ?? 'Unknown' }}
            @else
              Course
            @endif
          </span>
        </div>
        @endif
        @endforeach
      </div>
    </div>
    @endif

    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10 no-print">
      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-stone-200/80" style="border-top: 4px solid #6b2d39">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-md flex items-center justify-center flex-shrink-0" style="background:rgba(107,45,57,0.08)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#6b2d39">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4z"/>
            </svg>
          </div>
          <h4 class="font-semibold text-stone-800">Room Locations</h4>
        </div>
        <ul class="space-y-2 text-sm text-stone-600">
          <li class="flex items-start gap-2">
            <span class="font-semibold text-stone-800 w-8 flex-shrink-0">R-1</span>
            Ground Floor — Theory Classes
          </li>
          <li class="flex items-start gap-2">
            <span class="font-semibold text-stone-800 w-8 flex-shrink-0">R-2</span>
            First Floor — Lectures
          </li>
          <li class="flex items-start gap-2">
            <span class="font-semibold text-stone-800 w-8 flex-shrink-0">Lab</span>
            Ground Floor — Computer Lab
          </li>
          <li class="flex items-start gap-2">
            <span class="font-semibold text-stone-800 w-8 flex-shrink-0">Hall</span>
            Seminar Hall — Main Building
          </li>
        </ul>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-stone-200/80" style="border-top: 4px solid #c4973a">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-md flex items-center justify-center flex-shrink-0" style="background:rgba(196,151,58,0.1)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#c4973a">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h4 class="font-semibold text-stone-800">Break Times</h4>
        </div>
        <ul class="space-y-3 text-sm text-stone-600">
          <li class="flex justify-between items-center py-2 border-b border-stone-100">
            <span>Morning Break</span>
            <span class="font-semibold text-stone-800 text-xs bg-stone-100 px-2 py-0.5 rounded-full">10:00 – 10:15</span>
          </li>
          <li class="flex justify-between items-center py-2 border-b border-stone-100">
            <span>Lunch / Prayer</span>
            <span class="font-semibold text-stone-800 text-xs bg-stone-100 px-2 py-0.5 rounded-full">12:15 – 13:00</span>
          </li>
          <li class="flex justify-between items-center py-2">
            <span>Jumu'ah (Friday)</span>
            <span class="font-semibold text-stone-800 text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">12:30 – 14:00</span>
          </li>
        </ul>
        <p class="text-xs text-stone-500 mt-3 leading-relaxed">
          Classes resume promptly after each break. Please arrive on time.
        </p>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-stone-200/80" style="border-top: 4px solid #0f766e">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-md flex items-center justify-center flex-shrink-0 bg-teal-50">
            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
          </div>
          <h4 class="font-semibold text-stone-800">Contact for Changes</h4>
        </div>
        <p class="text-sm text-stone-600 mb-4 leading-relaxed">
          For timetable corrections, room conflicts, or special class arrangements, please contact:
        </p>
        <ul class="space-y-2 text-sm">
          <li class="flex items-center gap-2 text-stone-700">
            <svg class="w-4 h-4 text-stone-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Principal: <strong>{{ $college->principal_name ?? 'Arif Ali' }}</strong></span>
          </li>
          <li class="flex items-center gap-2 text-stone-700">
            <svg class="w-4 h-4 text-stone-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <a href="mailto:{{ $college->email ?? 'info@jdca.edu.pk' }}" class="text-teal-700 hover:underline">
              {{ $college->email ?? 'info@jdca.edu.pk' }}
            </a>
          </li>
          <li class="flex items-center gap-2 text-stone-700">
            <svg class="w-4 h-4 text-stone-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>{{ $college->address ?? 'Astore, Gilgit-Baltistan' }}</span>
          </li>
        </ul>
        <a href="{{ route('contact') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-teal-800 hover:text-teal-900 transition">
          Visit Contact Page
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>

    <div class="mt-6 flex items-start gap-3 p-4 rounded-xl no-print" style="background:rgba(107,45,57,0.05); border:1px solid rgba(107,45,57,0.12)">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" style="color:#6b2d39">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
      </svg>
      <p class="text-xs leading-relaxed" style="color:#6b2d39">
        <strong>Note:</strong> This timetable is subject to change. Last-minute changes will be communicated by faculty directly.
        Always verify with your class representative or department office.
        {{ $college->college_name ?? 'Jinnah School & Degree College Astore' }} — Affiliated with Karakoram International University (KIU).
      </p>
    </div>
  </div>
</section>

@endsection
