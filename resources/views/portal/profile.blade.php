@extends('layouts.portal')
@section('title', 'My Profile')
@section('content')

<div class="max-w-5xl mx-auto space-y-6">

  {{-- Profile header card --}}
  <div class="portal-card rounded-3xl overflow-hidden">
    <div class="h-32 relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 45%, #0f172a 100%)">
      <div class="absolute inset-0 opacity-40" style="background: radial-gradient(circle at top right, rgba(196,151,58,0.4), transparent 24%)"></div>
    </div>
    <div class="px-6 pb-6">
      <div class="flex flex-col lg:flex-row lg:items-end gap-5 -mt-10 mb-6 relative">
        <div class="w-24 h-24 rounded-3xl flex items-center justify-center text-white font-black text-4xl flex-shrink-0 shadow-2xl"
             style="background: linear-gradient(135deg, #c4973a 0%, #d6ad63 100%); border: 4px solid #0f172a">
          {{ strtoupper(substr($student->name, 0, 1)) }}
        </div>
        <div class="flex-1 pb-1">
          <div class="text-xs uppercase tracking-[0.25em] text-slate-400 mb-2">Student Profile</div>
          <h2 class="text-2xl font-bold text-slate-50">{{ $student->name }}</h2>
          <p class="text-sm text-slate-400 mt-1">{{ $student->academicProgram?->name ?? 'Program not assigned' }}</p>
          <div class="flex flex-wrap gap-2 mt-4">
            <span class="portal-chip">{{ $student->registration_number ?: $student->roll_number }}</span>
            <span class="portal-chip" style="background: rgba(59,130,246,0.1); color: #bfdbfe; border-color: rgba(96,165,250,0.18)">
              Semester {{ $student->current_semester ?? '—' }}
            </span>
            <span class="portal-chip" style="background: rgba(196,151,58,0.16); color: #fde68a; border-color: rgba(245,158,11,0.2)">
              Section {{ $student->section ?? '—' }}
            </span>
          </div>
        </div>
        <div class="flex gap-2 flex-wrap">
          <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold capitalize"
                style="background: rgba(22,163,74,0.14); color: #86efac; border: 1px solid rgba(34,197,94,0.18)">
            {{ $student->status instanceof \BackedEnum ? $student->status->value : ($student->status ?? 'active') }}
          </span>
          <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold"
                style="background: rgba(148,163,184,0.12); color: #cbd5e1; border: 1px solid rgba(148,163,184,0.14)">
            Batch {{ $student->batch_year ?? '—' }}
          </span>
        </div>
      </div>

      {{-- Info grid --}}
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-5" style="border-top: 1px solid rgba(148,163,184,0.14)">
        @foreach([
          ['label' => 'Registration Number', 'value' => $student->registration_number ?: $student->roll_number],
          ['label' => 'Batch Year',     'value' => $student->batch_year ?? '—'],
          ['label' => 'Semester',       'value' => 'Semester ' . ($student->current_semester ?? '—')],
          ['label' => 'Section',        'value' => $student->section ?? '—'],
        ] as $f)
        <div class="rounded-2xl px-4 py-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(148,163,184,0.1)">
          <div class="text-[11px] font-semibold uppercase tracking-wide mb-1 text-slate-500">{{ $f['label'] }}</div>
          <div class="text-sm font-semibold text-slate-100">{{ $f['value'] }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Academic Information --}}
    <div class="portal-card rounded-2xl p-6">
      <h3 class="font-semibold text-slate-50 mb-5 flex items-center gap-2">
        <span class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(96,165,250,0.18)">
        <svg class="w-4 h-4" style="color: #93c5fd" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        </svg>
        </span>
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
        <div class="flex justify-between items-start gap-4 py-2.5" style="border-bottom: 1px solid rgba(148,163,184,0.08)">
          <dt class="text-xs font-medium text-slate-500 flex-shrink-0">{{ $label }}</dt>
          <dd class="text-sm text-slate-200 font-medium text-right">{{ $value }}</dd>
        </div>
        @endforeach
      </dl>
    </div>

    {{-- Personal Information --}}
    <div class="portal-card rounded-2xl p-6">
      <h3 class="font-semibold text-slate-50 mb-5 flex items-center gap-2">
        <span class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(196,151,58,0.12); border: 1px solid rgba(245,158,11,0.18)">
        <svg class="w-4 h-4" style="color: #fcd34d" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        </span>
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
        <div class="flex justify-between items-start gap-4 py-2.5" style="border-bottom: 1px solid rgba(148,163,184,0.08)">
          <dt class="text-xs font-medium text-slate-500 flex-shrink-0">{{ $label }}</dt>
          <dd class="text-sm text-slate-200 font-medium text-right">{{ $value }}</dd>
        </div>
        @endforeach
      </dl>
    </div>
  </div>

  {{-- Change Password --}}
  <div class="portal-card rounded-2xl p-6">
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-5">
      <div>
        <h3 class="font-semibold text-slate-50 mb-1 flex items-center gap-2">
          <span class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: rgba(168,85,247,0.12); border: 1px solid rgba(192,132,252,0.18)">
          <svg class="w-4 h-4" style="color: #c4b5fd" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
      </svg>
          </span>
          Change Password
        </h3>
        <p class="text-sm text-slate-400">Keep your portal account secure with a unique password.</p>
      </div>
      <div class="rounded-2xl px-4 py-3 text-xs text-slate-400 max-w-sm" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(148,163,184,0.1)">
        Current password is required. If you never changed it before, your default password is your roll number.
      </div>
    </div>

    @if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-xl text-sm text-rose-200" style="background: rgba(239,68,68,0.14); border: 1px solid rgba(248,113,113,0.22)">
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ route('portal.profile.password') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold text-slate-400 mb-1.5">Current Password</label>
          <input type="password" name="current_password" required
                 class="w-full px-3.5 py-2.5 text-sm rounded-xl focus:outline-none transition"
                 style="border: 1px solid rgba(148,163,184,0.18); background: rgba(2,6,23,0.45); color: #e2e8f0"
                 onfocus="this.style.borderColor='rgba(96,165,250,0.55)';this.style.background='rgba(15,23,42,0.75)'"
                 onblur="this.style.borderColor='rgba(148,163,184,0.18)';this.style.background='rgba(2,6,23,0.45)'"
                 placeholder="Current password">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-400 mb-1.5">New Password</label>
          <input type="password" name="password" required minlength="6"
                 class="w-full px-3.5 py-2.5 text-sm rounded-xl focus:outline-none transition"
                 style="border: 1px solid rgba(148,163,184,0.18); background: rgba(2,6,23,0.45); color: #e2e8f0"
                 onfocus="this.style.borderColor='rgba(96,165,250,0.55)';this.style.background='rgba(15,23,42,0.75)'"
                 onblur="this.style.borderColor='rgba(148,163,184,0.18)';this.style.background='rgba(2,6,23,0.45)'"
                 placeholder="Min. 6 characters">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-400 mb-1.5">Confirm New Password</label>
          <input type="password" name="password_confirmation" required minlength="6"
                 class="w-full px-3.5 py-2.5 text-sm rounded-xl focus:outline-none transition"
                 style="border: 1px solid rgba(148,163,184,0.18); background: rgba(2,6,23,0.45); color: #e2e8f0"
                 onfocus="this.style.borderColor='rgba(96,165,250,0.55)';this.style.background='rgba(15,23,42,0.75)'"
                 onblur="this.style.borderColor='rgba(148,163,184,0.18)';this.style.background='rgba(2,6,23,0.45)'"
                 placeholder="Repeat new password">
        </div>
      </div>
      <div class="mt-4 flex justify-end">
        <button type="submit"
                class="portal-btn-primary px-5 py-2.5 text-sm font-semibold rounded-xl transition">
          Update Password
        </button>
      </div>
    </form>
  </div>

  {{-- Info note --}}
  <div class="px-5 py-4 rounded-2xl text-sm" style="background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.2)">
    <p class="text-amber-200">
      <strong>Note:</strong> To update your personal information (name, CNIC, address, etc.), please visit the college office.
      Your portal password can be reset by office staff if needed.
    </p>
  </div>

</div>
@endsection
