<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\ManagesPageSections;
use App\Filament\Resources\LeadershipMessageResource;
use App\Models\LeadershipMessage;
use Filament\Pages\Page;

class AboutUsSections extends Page
{
    use ManagesPageSections;

    protected static ?string $navigationGroup = 'Website Pages';

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationLabel = 'About Us';

    protected static ?string $title = 'About Us — Sections';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.sections-manager';

    public function getSections(): array
    {
        $leaders = LeadershipMessage::orderBy('sort_order')->pluck('designation', 'name');
        $detail = $leaders->map(fn ($designation, $name) => "{$name} ({$designation})")->implode(' · ');

        return [
            $this->pageRow('about', 'About (intro)'),
            $this->pageRow('about-history', 'History & Geography'),
            $this->pageRow('about-mission', 'Mission & Vision'),
            $this->collectionRow(
                'Leadership Messages',
                $leaders->count(),
                LeadershipMessageResource::getUrl('index'),
                ['detail' => $detail ?: null]
            ),
            $this->pageRow('campus-facilities', 'Campus Facilities'),
            $this->pageRow('gallery', 'Campus Gallery'),
        ];
    }
}
