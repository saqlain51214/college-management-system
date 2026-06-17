@extends('layouts.portal')
@section('title', 'My Profile')
@section('content')

<div class="max-w-4xl mx-auto space-y-6">

  {{-- Profile header card --}}
  <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
    <div class="h-24 relative" style="background: linear-gradient(135deg, #1e3a5f 0%, #2a4f80 100%)"></div>
    <div class="px-6 pb-6">
      <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-8 mb-4">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white font-black text-3xl flex-shrink-0 shadow-lg"
             style="background: #c4973a; border: 4px solid white">
          {{ strtoupper(substr($student->name, 0, 1)) }}
        </div>
        <div class="flex-1 pb-1">
          <h2 class="text-xl font-bold text-gray-800">{{ $student->name }}</h2>
          <p class="text-sm text-gray-500 mt-0.5">{{ $student->academicProgram?->name ?? 'Program not assigned' }}</p>
        </div>
        <div class="flex gap-2">
          <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold"
                style="background: #eff6ff; color: #1e3a5f">
            {{ $student->roll_number }}
          </span>
          <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold capitalize"
                style="background: #f0fdf4; color: #15803d">
            {{ $student->status instanceof \BackedEnum ? $student->status->value : ($student->status ?? 'active') }}
          </span>
        </div>
      </div>

      {{-- Info grid --}}
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4" style="border-top: 1px solid #f3f4f6">
        @foreach([
          ['label' => 'Roll Number',    'value' => $student->roll_number],
          ['label' => 'Batch Year',     'value' => $student->batch_year ?? '—'],
          ['label' => 'Semester',       'value' => 'Semester ' . ($student->current_semester ?? '—')],
          ['label' => 'Section',        'value' => $student->section ?? '—'],
        ] as $f)
        <div>
          <div class="text-[11px] font-semibold uppercase tracking-wide mb-1" style="color: #9ca3af">{{ $f['label'] }}</div>
          <div class="text-sm font-semibold text-gray-700">{{ $f['value'] }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Academic Information --}}
    <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
      <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <svg class="w-4 h-4" style="color: #1e3a5f" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        </svg>
        Academic Information
      </h3>
      <dl class="space-y-3">
        @foreach([
          ['Program',         $student->academicProgram?->name ?? '—'],
          ['Department',      $student->department?->name ?? '—'],
          ['Academic Year',   $student->academicYear?->name ?? '—'],
          ['Reg. Number',     $student->registration_number ?? '—'],
          ['Admission Date',  $student->admission_date?->format('d M Y') ?? '—'],
          ['Admission Type',  $student->admission_type instanceof \BackedEnum ? ucfirst($student->admission_type->value) : ($student->admission_type ?? '—')],
          ['Previous Qual.',  $student->previous_qualification ?? '—'],
        ] as [$label, $value])
        <div class="flex justify-between items-start gap-4 py-2" style="border-bottom: 1px solid #f9fafb">
          <dt class="text-xs font-medium text-gray-400 flex-shrink-0">{{ $label }}</dt>
          <dd class="text-sm text-gray-700 font-medium text-right">{{ $value }}</dd>
        </div>
        @endforeach
      </dl>
    </div>

    {{-- Personal Information --}}
    <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
      <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <svg class="w-4 h-4" style="color: #1e3a5f" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Personal Information
      </h3>
      <dl class="space-y-3">
        @foreach([
          ['Father\'s Name', $student->father_name ?? '—'],
          ['Date of Birth',  $student->date_of_birth?->format('d M Y') ?? '—'],
          ['Gender',         $student->gender instanceof \BackedEnum ? ucfirst($student->gender->value) : ($student->gender ?? '—')],
          ['CNIC / B-Form',  $student->cnic ?? '—'],
          ['Phone',          $student->phone ?? '—'],
          ['Email',          $student->email ?? '—'],
          ['Address',        $student->city ? ($student->city . ', ' . ($student->district ?? '')) : ($student->address ?? '—')],
        ] as [$label, $value])
        <div class="flex justify-between items-start gap-4 py-2" style="border-bottom: 1px solid #f9fafb">
          <dt class="text-xs font-medium text-gray-400 flex-shrink-0">{{ $label }}</dt>
          <dd class="text-sm text-gray-700 font-medium text-right">{{ $value }}</dd>
        </div>
        @endforeach
      </dl>
    </div>
  </div>

  {{-- Change Password --}}
  <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
    <h3 class="font-semibold text-gray-800 mb-1 flex items-center gap-2">
      <svg class="w-4 h-4" style="color: #1e3a5f" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
      </svg>
      Change Password
    </h3>
    <p class="text-xs text-gray-400 mb-5">Leave blank to keep your current password.</p>

    @if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-xl text-sm text-red-800" style="background: #fef2f2; border: 1px solid #fecaca">
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ route('portal.profile.password') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1.5">Current Password</label>
          <input type="password" name="current_password" required
                 class="w-full px-3.5 py-2.5 text-sm rounded-xl focus:outline-none transition"
                 style="border: 1px solid #d1d5db; background: #f9fafb"
                 onfocus="this.style.borderColor='#1e3a5f';this.style.background='#fff'"
                 onblur="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'"
                 placeholder="Current password">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1.5">New Password</label>
          <input type="password" name="password" required minlength="6"
                 class="w-full px-3.5 py-2.5 text-sm rounded-xl focus:outline-none transition"
                 style="border: 1px solid #d1d5db; background: #f9fafb"
                 onfocus="this.style.borderColor='#1e3a5f';this.style.background='#fff'"
                 onblur="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'"
                 placeholder="Min. 6 characters">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1.5">Confirm New Password</label>
          <input type="password" name="password_confirmation" required minlength="6"
                 class="w-full px-3.5 py-2.5 text-sm rounded-xl focus:outline-none transition"
                 style="border: 1px solid #d1d5db; background: #f9fafb"
                 onfocus="this.style.borderColor='#1e3a5f';this.style.background='#fff'"
                 onblur="this.style.borderColor='#d1d5db';this.style.background='#f9fafb'"
                 placeholder="Repeat new password">
        </div>
      </div>
      <div class="mt-4 flex justify-end">
        <button type="submit"
                class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition"
                style="background: #1e3a5f"
                onmouseover="this.style.background='#2a4f80'" onmouseout="this.style.background='#1e3a5f'">
          Update Password
        </button>
      </div>
    </form>
  </div>

  {{-- Info note --}}
  <div class="px-5 py-4 rounded-2xl text-sm" style="background: #fffbeb; border: 1px solid #fde68a">
    <p class="text-amber-800">
      <strong>Note:</strong> To update your personal information (name, CNIC, address, etc.), please visit the college office.
      Your portal password can be reset by office staff if needed.
    </p>
  </div>

</div>
@endsection
