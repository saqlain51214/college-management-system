<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([

                // ── Full-width banner ────────────────────────────────────────
                View::make('filament.components.profile-banner')
                    ->columnSpanFull(),

                // ── LEFT: Avatar card ────────────────────────────────────────
                Section::make()
                    ->columnSpan(['default' => 3, 'lg' => 1])
                    ->schema([

                        FileUpload::make('avatar')
                            ->label('Profile Photo')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('avatars')
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpanFull(),

                        Placeholder::make('role_display')
                            ->label('Assigned Roles')
                            ->content(function (): HtmlString {
                                $roles = auth()->user()->getRoleNames();
                                if ($roles->isEmpty()) {
                                    return new HtmlString('<span class="text-gray-400 text-sm italic">No role assigned</span>');
                                }
                                $badges = $roles->map(function ($role) {
                                    $label = ucwords(str_replace('_', ' ', $role));
                                    return '<span style="display:inline-flex;align-items:center;gap:4px;margin:2px;padding:3px 12px;border-radius:9999px;background:rgba(245,158,11,0.12);color:#b45309;font-size:0.72rem;font-weight:700;letter-spacing:0.05em;border:1px solid rgba(245,158,11,0.35)">'
                                        . '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>'
                                        . e($label) . '</span>';
                                })->implode('');
                                return new HtmlString('<div style="display:flex;flex-wrap:wrap;gap:2px">' . $badges . '</div>');
                            })
                            ->columnSpanFull(),

                        Placeholder::make('member_since')
                            ->label('Member Since')
                            ->content(fn () => auth()->user()->created_at->format('d M Y'))
                            ->columnSpanFull(),

                        Placeholder::make('last_updated')
                            ->label('Last Updated')
                            ->content(fn () => auth()->user()->updated_at->diffForHumans())
                            ->columnSpanFull(),
                    ]),

                // ── RIGHT: Forms ─────────────────────────────────────────────
                Grid::make(1)
                    ->columnSpan(['default' => 3, 'lg' => 2])
                    ->schema([

                        Section::make('Personal Information')
                            ->icon('heroicon-o-user-circle')
                            ->description('Update your name, email address and contact number.')
                            ->columns(2)
                            ->schema([

                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user')
                                    ->columnSpanFull(),

                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->unique(table: 'users', column: 'email', ignorable: auth()->user())
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->columnSpan(1),

                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('+92-300-0000000')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->columnSpan(1),
                            ]),

                        Section::make('Change Password')
                            ->icon('heroicon-o-lock-closed')
                            ->description('Leave new password blank to keep your existing password.')
                            ->columns(2)
                            ->schema([

                                TextInput::make('current_password')
                                    ->label('Current Password')
                                    ->password()
                                    ->revealable()
                                    ->required()
                                    ->prefixIcon('heroicon-o-key')
                                    ->helperText('Required to confirm any changes.')
                                    ->columnSpanFull()
                                    ->rules([
                                        fn () => function (string $attribute, mixed $value, \Closure $fail) {
                                            if (! Hash::check($value, auth()->user()->password)) {
                                                $fail('The current password is incorrect.');
                                            }
                                        },
                                    ]),

                                TextInput::make('password')
                                    ->label('New Password')
                                    ->password()
                                    ->revealable()
                                    ->nullable()
                                    ->minLength(8)
                                    ->same('password_confirmation')
                                    ->prefixIcon('heroicon-o-lock-closed')
                                    ->helperText('Minimum 8 characters.')
                                    ->columnSpan(1),

                                TextInput::make('password_confirmation')
                                    ->label('Confirm New Password')
                                    ->password()
                                    ->revealable()
                                    ->nullable()
                                    ->prefixIcon('heroicon-o-shield-check')
                                    ->dehydrated(false)
                                    ->columnSpan(1),
                            ]),
                    ]),

            ])
            ->columns(3)
            ->statePath('data');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = auth()->user();

        return array_merge($data, [
            'name'                  => $user->name,
            'email'                 => $user->email,
            'phone'                 => $user->phone,
            'avatar'                => $user->avatar,
            'current_password'      => null,
            'password'              => null,
            'password_confirmation' => null,
        ]);
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $update = [
            'name'   => $data['name'],
            'email'  => $data['email'],
            'phone'  => $data['phone']  ?? null,
            'avatar' => $data['avatar'] ?? null,
        ];

        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $record->update($update);

        return $record;
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Profile updated')
            ->body('Your changes have been saved successfully.');
    }
}
