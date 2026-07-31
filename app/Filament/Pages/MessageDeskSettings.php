<?php

namespace App\Filament\Pages;

use App\Models\CollegeSetting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

/**
 * A dedicated page for picking which of the 6 Leadership Message Desk
 * designs is shown on the home page. Reached only via Website Pages → Home
 * Page → "Message Desk Design" row's Edit link — not a separate sidebar entry.
 */
class MessageDeskSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Message Desk Design';

    protected static ?string $title = 'Leadership Message Desk — Design';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    protected static string $view = 'filament.pages.message-desk-settings';

    public string $template = 'cards';

    /** @var array<string,array{label:string,description:string}> */
    public static array $templates = [
        'cards'     => ['label' => 'Modern Leadership Cards', 'description' => 'Equal-height white cards, large photo block, navy/gold accent. The safest, most classic option.'],
        'spotlight' => ['label' => 'Leadership Spotlight', 'description' => 'Dark editorial layout — one featured leader (image + pull-quote) plus the rest in a compact row.'],
        'diploma'   => ['label' => 'Diploma Frame', 'description' => 'Ornamental double-border frame with a small seal icon — a ceremonial, academic feel.'],
        'ribbon'    => ['label' => 'Split Ribbon', 'description' => 'Compact horizontal rows with a navy photo panel — scans fast for a long leadership list.'],
        'glass'     => ['label' => 'Glass Halo', 'description' => 'Frosted-glass card with a soft gold glow around a circular photo — the most modern/trendy option.'],
        'minimal'   => ['label' => 'Minimal Editorial', 'description' => 'No card box at all — generous whitespace, a numbered index, and a hairline rule. The quietest option.'],
    ];

    public function mount(): void
    {
        // Legacy aliases from before the 6-template picker existed.
        $legacy = ['card' => 'cards', 'side_by_side' => 'spotlight'];
        $raw = CollegeSetting::get('message_desk_layout', 'cards');
        $this->template = $legacy[$raw] ?? $raw;

        if (! array_key_exists($this->template, self::$templates)) {
            $this->template = 'cards';
        }
    }

    public function save(): void
    {
        CollegeSetting::set('message_desk_layout', $this->template, 'website');
        Cache::flush();

        Notification::make()
            ->title('Message Desk template updated')
            ->body('The home page now shows the "' . (self::$templates[$this->template]['label'] ?? $this->template) . '" design.')
            ->success()
            ->send();
    }
}
