<x-filament-panels::page>
    @php $d = $this->diagnostics(); @endphp

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <x-filament::section heading="Environment">
            <dl class="space-y-2 text-sm">
                @foreach ($d['env'] as $key => $value)
                    <div class="flex justify-between gap-4">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">{{ $key }}</dt>
                        <dd class="font-mono">{{ $value ?: '—' }}</dd>
                    </div>
                @endforeach
            </dl>
        </x-filament::section>

        <x-filament::section heading="PHP Upload / Execution Limits">
            <dl class="space-y-2 text-sm">
                @foreach ($d['php'] as $key => $value)
                    <div class="flex justify-between gap-4">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">{{ $key }}</dt>
                        <dd class="font-mono">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                Upload fields on this site allow up to 10 MB — <strong>upload_max_filesize</strong> and
                <strong>post_max_size</strong> above must both be 10M or higher, or uploads near that size will fail
                silently. If either is too low, it must be raised in cPanel &rarr; MultiPHP INI Editor for this domain.
            </p>
        </x-filament::section>

        <x-filament::section heading="Storage — Writable Folders">
            <ul class="space-y-2 text-sm">
                @foreach ($d['writable'] as $row)
                    <li class="flex items-center justify-between gap-4">
                        <span>{{ $row['label'] }}</span>
                        @if (! $row['exists'])
                            <x-filament::badge color="danger">Missing</x-filament::badge>
                        @elseif (! $row['writable'])
                            <x-filament::badge color="danger">Not writable</x-filament::badge>
                        @else
                            <x-filament::badge color="success">OK</x-filament::badge>
                        @endif
                    </li>
                @endforeach
                <li class="flex items-center justify-between gap-4">
                    <span>public/storage symlink</span>
                    @if ($d['storage_symlink'])
                        <x-filament::badge color="success">OK</x-filament::badge>
                    @else
                        <x-filament::badge color="danger">Missing</x-filament::badge>
                    @endif
                </li>
            </ul>
            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                "Not writable" or "Missing" here is the most common cause of an image/file upload failing on this
                server. Fix folder permissions in cPanel File Manager, or use "Re-create Storage Link" above if only
                the symlink is missing.
            </p>
        </x-filament::section>

        <x-filament::section heading="Log &amp; Disk Space">
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Current log file (laravel.log)</dt>
                    <dd class="font-mono">{{ $d['log_file_size'] }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="font-medium text-gray-500 dark:text-gray-400">storage/logs total</dt>
                    <dd class="font-mono">{{ $d['logs_dir_total'] }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Free disk space</dt>
                    <dd class="font-mono">{{ $d['disk_free'] }}</dd>
                </div>
            </dl>
        </x-filament::section>
    </div>
</x-filament-panels::page>
