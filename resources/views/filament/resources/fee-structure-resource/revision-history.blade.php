<div class="space-y-2">
    @forelse($revisions as $rev)
        <div class="rounded-lg border border-gray-200 p-3 text-sm dark:border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="font-semibold text-gray-800 dark:text-gray-100">
                    Rs. {{ number_format((float) $rev->old_amount) }} &rarr; Rs. {{ number_format((float) $rev->new_amount) }}
                </span>
                <span class="text-xs text-gray-500">Effective {{ $rev->effective_from?->format('d M Y') }}</span>
            </div>
            <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">{{ $rev->reason }}</p>
            <p class="mt-1 text-[11px] text-gray-400">Changed by {{ $rev->changedBy?->name ?? 'System' }} on {{ $rev->created_at?->format('d M Y, h:i A') }}</p>
        </div>
    @empty
        <p class="text-sm text-gray-400">No amount changes recorded yet.</p>
    @endforelse
</div>
