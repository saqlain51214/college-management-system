@extends('layouts.teacher-portal')
@section('title', 'Notices')
@section('content')

<div class="space-y-6">
  <div>
    <h2 class="text-xl font-bold text-gray-900">Notices</h2>
    <p class="text-sm text-gray-500 mt-1">Published notices visible to teachers.</p>
  </div>

  <div class="space-y-4">
    @forelse($notices as $notice)
      <div class="bg-white rounded-2xl p-6" style="border: 1px solid #e5e7eb">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="font-semibold text-gray-800">{{ $notice->title }}</h3>
            <div class="text-xs text-gray-500 mt-1">
              {{ $notice->publish_date?->format('d M Y') ?? '—' }}
              @if($notice->priority)
                • {{ ucfirst($notice->priority) }} priority
              @endif
            </div>
          </div>
          <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
            {{ ucfirst($notice->audience ?? 'all') }}
          </span>
        </div>
        <div class="mt-4 text-sm text-gray-600 leading-6 whitespace-pre-line">{{ $notice->content }}</div>
      </div>
    @empty
      <div class="bg-white rounded-2xl p-8 text-sm text-gray-400 text-center" style="border: 1px solid #e5e7eb">
        No notices available right now.
      </div>
    @endforelse
  </div>

  @if($notices->hasPages())
    <div class="bg-white rounded-2xl px-5 py-4" style="border: 1px solid #e5e7eb">
      {{ $notices->links() }}
    </div>
  @endif
</div>
@endsection
