<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\WebsitePagesCluster;
use App\Filament\Concerns\ManagesPageSections;
use App\Filament\Resources\HeroSlideResource;
use App\Filament\Resources\HomeSectionResource;
use App\Filament\Resources\LeadershipMessageResource;
use App\Filament\Resources\NewsArticleResource;
use App\Models\HeroSlide;
use App\Models\HomeSection;
use App\Models\LeadershipMessage;
use App\Models\NewsArticle;
use Filament\Pages\Page;

/**
 * Every section that composes the homepage, one row each — replaces hunting
 * across Homepage Slider / Home Sections / Message Desk / Website Pages
 * separately. See App\Filament\Concerns\ManagesPageSections for the shared
 * rename/toggle logic every "Sections" page uses.
 */
class HomePageSections extends Page
{
    use ManagesPageSections;

    protected static ?string $cluster = WebsitePagesCluster::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Home Page';

    protected static ?string $title = 'Home Page — Sections';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.sections-manager';

    public function getSections(): array
    {
        return [
            $this->collectionRow('Hero Slider', HeroSlide::count(), HeroSlideResource::getUrl('index')),

            [
                'kind' => 'setting',
                'name' => 'Message Desk Design',
                'editUrl' => MessageDeskSettings::getUrl(),
            ],

            $this->collectionRow(
                'Leadership Messages',
                LeadershipMessage::count(),
                LeadershipMessageResource::getUrl('index'),
                ['crossLink' => 'Managed under About Us — shown here for reference only.']
            ),

            $this->pageRow('home', 'Featured Programmes (teaser)'),

            $this->collectionRow('Latest News', NewsArticle::count(), NewsArticleResource::getUrl('index')),

            $this->collectionRow(
                'Home Sections (Elevate Learning / Campus Life / Testimonials)',
                HomeSection::where('is_active', true)->count(),
                HomeSectionResource::getUrl('index'),
                ['detail' => 'Now rendering live on the homepage — manage each one\'s content and active/inactive status here.']
            ),
        ];
    }
}
