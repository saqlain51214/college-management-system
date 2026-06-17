@extends('layouts.portal')
@section('title', 'Notices')
@section('content')

@if($notices->isEmpty())
<div class="bg-white rounded-2xl p-16 text-center" style="border: 1px solid #e5e7eb">
  <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #f3f4f6">
    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>
  </div>
  <h3 class="font-semibold text-gray-500 mb-1">No Notices</h3>
  <p class="text-sm text-gray-400">Official announcements will appear here.</p>
</div>
@else
<div class="space-y-3">
  @foreach($notices as $notice)
  @php
    $priority = $notice->priority ?? 'normal';
    $priorityStyle = match($priority) {
      'urgent' => 'background:#fee2e2;color:#dc2626',
      'high'   => 'background:#fef3c7;color:#d97706',
      default  => 'background:#eff6ff;color:#1d4ed8',
    };
    $borderStyle = match($priority) {
      'urgent' => 'border-left: 4px solid #ef4444',
      'high'   => 'border-left: 4px solid #f59e0b',
      default  => 'border-left: 4px solid #3b82f6',
    };
  @endphp
  <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e5e7eb; {{ $borderStyle }}">
    <div class="px-5 py-4">
      <div class="flex items-start justify-between gap-4">
        <div class="flex items-start gap-3 flex-1 min-w-0">
          <span class="text-[11px] font-bold px-2.5 py-1 rounded-full flex-shrink-0 mt-0.5 uppercase"
                style="{{ $priorityStyle }}">
            {{ $priority }}
          </span>
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-900 text-sm leading-snug">{{ $notice->title }}</h3>
            @if($notice->content)
            <div class="text-sm text-gray-500 mt-2 leading-relaxed">
              {!! Str::limit(strip_tags($notice->content), 250) !!}
            </div>
            @endif
            <div class="flex flex-wrap items-center gap-4 mt-3">
              @if($notice->publish_date)
              <span class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Posted: {{ \Carbon\Carbon::parse($notice->publish_date)->format('d M Y') }}
              </span>
              @endif
              @if($notice->expiry_date)
              <span class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Expires: {{ \Carbon\Carbon::parse($notice->expiry_date)->format('d M Y') }}
              </span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>
<div class="mt-5">{{ $notices->links() }}</div>
@endif

@endsection
