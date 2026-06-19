@extends('layouts.teacher-portal')
@section('title', 'My Profile')
@section('content')

<div class="max-w-5xl mx-auto space-y-6">
  <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb">
    <div class="h-24" style="background: linear-gradient(135deg, #17324f 0%, #2a4f80 100%)"></div>
    <div class="px-6 pb-6">
      <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-8 mb-4">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white font-black text-3xl flex-shrink-0 shadow-lg"
             style="background: #c4973a; border: 4px solid white">
          {{ strtoupper(substr($teacher->name, 0, 1)) }}
        </div>
        <div class="flex-1 pb-1">
          <h2 class="text-xl font-bold text-gray-800">{{ $teacher->name }}</h2>
          <p class="text-sm text-gray-500 mt-0.5">{{ $teacher->designation ?? 'Teacher' }}</p>
        </div>
        <div class="flex gap-2 flex-wrap">
          <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold" style="background: #eff6ff; color: #1e3a5f">
            {{ $teacher->employee_id }}
          </span>
          <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full font-semibold capitalize" style="background: #f0fdf4; color: #15803d">
            {{ $teacher->status instanceof \BackedEnum ? $teacher->status->value : ($teacher->status ?? 'active') }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4" style="border-top: 1px solid #f3f4f6">
        @foreach([
          ['label' => 'Employee ID', 'value' => $teacher->employee_id],
          ['label' => 'Department', 'value' => $teacher->department?->name ?? '—'],
          ['label' => 'Designation', 'value' => $teacher->designation ?? '—'],
          ['label' => 'Joining Date', 'value' => $teacher->joining_date?->format('d M Y') ?? '—'],
        ] as $field)
          <div>
            <div class="text-[11px] font-semibold uppercase tracking-wide mb-1" style="color: #9ca3af">{{ $field['label'] }}</div>
            <div class="text-sm font-semibold text-gray-700">{{ $field['value'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
      <h3 class="font-semibold text-gray-800 mb-4">Professional Information</h3>
      <dl class="space-y-3">
        @foreach([
          ['Qualification', $teacher->highest_qualification ?? '—'],
          ['Specialization', $teacher->specialization ?? '—'],
          ['Institution', $teacher->qualification_institution ?? '—'],
          ['Experience', $teacher->experience_years !== null ? $teacher->experience_years . ' years' : '—'],
          ['Employment Type', $teacher->employment_type instanceof \BackedEnum ? ucfirst($teacher->employment_type->value) : ($teacher->employment_type ?? '—')],
        ] as [$label, $value])
          <div class="flex justify-between items-start gap-4 py-2" style="border-bottom: 1px solid #f9fafb">
            <dt class="text-xs font-medium text-gray-400">{{ $label }}</dt>
            <dd class="text-sm text-gray-700 font-medium text-right">{{ $value }}</dd>
          </div>
        @endforeach
      </dl>
    </div>

    <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
      <h3 class="font-semibold text-gray-800 mb-4">Contact Information</h3>
      <dl class="space-y-3">
        @foreach([
          ['Email', $teacher->email ?? '—'],
          ['Phone', $teacher->phone ?? '—'],
          ['Alternative Phone', $teacher->alternative_phone ?? '—'],
          ['City', $teacher->city ?? '—'],
          ['Address', $teacher->address ?? '—'],
        ] as [$label, $value])
          <div class="flex justify-between items-start gap-4 py-2" style="border-bottom: 1px solid #f9fafb">
            <dt class="text-xs font-medium text-gray-400">{{ $label }}</dt>
            <dd class="text-sm text-gray-700 font-medium text-right">{{ $value }}</dd>
          </div>
        @endforeach
      </dl>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
    <h3 class="font-semibold text-gray-800 mb-1">Change Password</h3>
    <p class="text-xs text-gray-400 mb-5">Use your employee ID as the current password if you have never changed it before.</p>

    @if($errors->any())
      <div class="mb-4 px-4 py-3 rounded-xl text-sm text-red-800" style="background: #fef2f2; border: 1px solid #fecaca">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="{{ route('teacher.profile.password') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1.5">Current Password</label>
          <input type="password" name="current_password" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:border-navy focus:bg-white" placeholder="Current password">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1.5">New Password</label>
          <input type="password" name="password" required minlength="6" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:border-navy focus:bg-white" placeholder="Min. 6 characters">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-500 mb-1.5">Confirm New Password</label>
          <input type="password" name="password_confirmation" required minlength="6" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-gray-50 focus:outline-none focus:border-navy focus:bg-white" placeholder="Repeat new password">
        </div>
      </div>
      <div class="mt-4 flex justify-end">
        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition" style="background: #17324f">
          Update Password
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
