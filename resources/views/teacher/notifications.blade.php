@extends('layouts.teacher-portal')
@section('title', 'Notifications')
@section('content')

<div class="space-y-6">
  <div>
    <h2 class="text-xl font-bold text-gray-900">Notifications</h2>
    <p class="text-sm text-gray-500 mt-1">Salary, results, and other account updates sent to you.</p>
  </div>

  <div class="space-y-3">
    @forelse($notifications as $notification)
      @php $data = $notification->data; @endphp
      <div class="bg-white rounded-2xl p-5 flex items-start justify-between gap-4"
           style="border: 1px solid #e5e7eb; {{ $notification->read_at ? '' : 'background:#fdf8f0;' }}">
        <div class="min-w-0">
          <div class="flex items-center gap-2">
            @unless($notification->read_at)
              <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:#c4973a"></span>
            @endunless
            <h3 class="font-semibold text-gray-800">{{ $data['title'] ?? 'Notification' }}</h3>
          </div>
          <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $data['message'] ?? '' }}</p>
          <div class="text-xs text-gray-400 mt-2">{{ $notification->created_at->format('d M Y, h:i A') }}</div>
          @if(!empty($data['action_url']) && !empty($data['action_label']))
            <a href="{{ $data['action_url'] }}" class="inline-block mt-2 text-xs font-semibold" style="color:#6b2d39">{{ $data['action_label'] }} →</a>
          @endif
        </div>
        @unless($notification->read_at)
          <form action="{{ route('teacher.notifications.read', $notification->id) }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit" class="text-xs font-medium text-gray-400 hover:text-gray-600">Mark read</button>
          </form>
        @endunless
      </div>
    @empty
      <div class="bg-white rounded-2xl p-8 text-sm text-gray-400 text-center" style="border: 1px solid #e5e7eb">
        No notifications yet.
      </div>
    @endforelse
  </div>

  @if($notifications->hasPages())
    <div class="bg-white rounded-2xl px-5 py-4" style="border: 1px solid #e5e7eb">
      {{ $notifications->links() }}
    </div>
  @endif
</div>
@endsection
