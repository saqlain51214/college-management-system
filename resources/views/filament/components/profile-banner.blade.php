@php
    use Illuminate\Support\Facades\Storage;

    $user   = auth()->user();
    $roles  = $user->getRoleNames();

    $avatar = $user->avatar
        ? Storage::disk('public')->url($user->avatar)
        : asset('images/avatar-placeholder.svg');
@endphp

<div style="
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #1e3a5f 0%, #1a4b8c 60%, #1e6091 100%);
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
">
    <div style="
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 24px;
        padding: 28px 32px;
    ">

        {{-- Avatar --}}
        <div style="position: relative; flex-shrink: 0;">
            <img src="{{ $avatar }}"
                 alt="{{ $user->name }}"
                 style="
                    width: 88px;
                    height: 88px;
                    border-radius: 50%;
                    object-fit: cover;
                    border: 3px solid rgba(255,255,255,0.5);
                    box-shadow: 0 2px 12px rgba(0,0,0,0.3);
                    display: block;
                 ">
            <span style="
                position: absolute;
                bottom: 4px;
                right: 4px;
                width: 14px;
                height: 14px;
                background: #4ade80;
                border-radius: 50%;
                border: 2px solid white;
                display: block;
            "></span>
        </div>

        {{-- Name + Email + Roles --}}
        <div style="flex: 1; min-width: 200px;">
            <h2 style="margin: 0 0 4px; font-size: 1.5rem; font-weight: 700; color: #ffffff; line-height: 1.2;">
                {{ $user->name }}
            </h2>
            <p style="margin: 0 0 10px; font-size: 0.85rem; color: rgba(255,255,255,0.7);">
                {{ $user->email }}
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                @forelse ($roles as $role)
                    <span style="
                        display: inline-flex;
                        align-items: center;
                        gap: 4px;
                        padding: 3px 12px;
                        border-radius: 9999px;
                        background: rgba(255,255,255,0.18);
                        color: #ffffff;
                        font-size: 0.7rem;
                        font-weight: 600;
                        letter-spacing: 0.05em;
                        border: 1px solid rgba(255,255,255,0.25);
                    ">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                        {{ ucwords(str_replace('_', ' ', $role)) }}
                    </span>
                @empty
                    <span style="font-size: 0.75rem; color: rgba(255,255,255,0.5); font-style: italic;">No role assigned</span>
                @endforelse
            </div>
        </div>

        {{-- Stats --}}
        <div style="display: flex; gap: 0; margin-left: auto; flex-shrink: 0;">

            <div style="
                text-align: center;
                padding: 0 24px;
                border-right: 1px solid rgba(255,255,255,0.2);
            ">
                <p style="margin: 0; font-size: 1.6rem; font-weight: 700; color: #ffffff; line-height: 1;">{{ $roles->count() }}</p>
                <p style="margin: 4px 0 0; font-size: 0.65rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.08em;">
                    {{ $roles->count() === 1 ? 'Role' : 'Roles' }}
                </p>
            </div>

            <div style="
                text-align: center;
                padding: 0 24px;
                border-right: 1px solid rgba(255,255,255,0.2);
            ">
                <p style="margin: 0; font-size: 1.6rem; font-weight: 700; color: #ffffff; line-height: 1;">{{ $user->created_at->format('Y') }}</p>
                <p style="margin: 4px 0 0; font-size: 0.65rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.08em;">Joined</p>
            </div>

            <div style="text-align: center; padding: 0 0 0 24px;">
                <p style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #ffffff; line-height: 1;">{{ $user->updated_at->format('d M') }}</p>
                <p style="margin: 4px 0 0; font-size: 0.65rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.08em;">Updated</p>
            </div>

        </div>
    </div>
</div>
