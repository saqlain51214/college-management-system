<x-filament-panels::page>

    <style>
        /* Avatar upload centering */
        .avatar-upload .fi-fo-field-wrp,
        .avatar-upload .fi-fo-field-wrp > div {
            display: flex;
            justify-content: center;
        }
        .avatar-upload [data-fs-file-upload] {
            display: flex;
            justify-content: center;
        }

        /* Profile card polish */
        .profile-card-section .fi-section-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            text-align: center;
        }

        /* Role badge */
        .role-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgb(var(--primary-100));
            color: rgb(var(--primary-700));
            border: 1px solid rgb(var(--primary-200));
        }
        .dark .role-badge {
            background: rgb(var(--primary-900));
            color: rgb(var(--primary-200));
            border-color: rgb(var(--primary-800));
        }
    </style>

    {{-- Page header with avatar display --}}
    @php
        $user   = auth()->user();
        $avatar = $user->avatar
            ? Storage::disk('public')->url($user->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=random&bold=true';
        $roles  = $user->getRoleNames();
    @endphp

    {{-- Top Profile Banner --}}
    <div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-800 dark:to-primary-950 shadow-lg">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative flex flex-col sm:flex-row items-center gap-5 px-8 py-6">
            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                <img src="{{ $avatar }}" alt="{{ $user->name }}"
                     class="h-24 w-24 rounded-full border-4 border-white shadow-lg object-cover">
                <span class="absolute bottom-1 right-1 h-4 w-4 rounded-full bg-green-400 border-2 border-white"></span>
            </div>

            {{-- Info --}}
            <div class="text-center sm:text-left">
                <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-primary-200 text-sm mt-0.5">{{ $user->email }}</p>
                <div class="mt-2 flex flex-wrap gap-1.5 justify-center sm:justify-start">
                    @foreach ($roles as $role)
                        <span class="inline-flex items-center gap-1 rounded-full bg-white/20 px-3 py-0.5 text-xs font-semibold text-white ring-1 ring-white/30">
                            <x-heroicon-m-shield-check class="h-3 w-3" />
                            {{ ucwords(str_replace('_', ' ', $role)) }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Stats --}}
            <div class="sm:ml-auto flex gap-6 text-center">
                <div>
                    <p class="text-2xl font-bold text-white">{{ $user->created_at->format('Y') }}</p>
                    <p class="text-xs text-primary-200 uppercase tracking-wide">Joined</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $roles->count() }}</p>
                    <p class="text-xs text-primary-200 uppercase tracking-wide">{{ Str::plural('Role', $roles->count()) }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">{{ $user->updated_at->format('d M') }}</p>
                    <p class="text-xs text-primary-200 uppercase tracking-wide">Last Update</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <x-filament-panels::form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center justify-between pt-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                <x-heroicon-o-information-circle class="inline h-4 w-4 mr-1" />
                Current password is required to save any changes.
            </p>
            <x-filament::button type="submit" size="lg" icon="heroicon-o-check-circle">
                Save Profile
            </x-filament::button>
        </div>
    </x-filament-panels::form>

</x-filament-panels::page>
