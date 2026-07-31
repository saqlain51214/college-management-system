<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Single home for everything about the public website — the 4 Section
 * Manager pages (Home Page / About Us / Academics / Admission) plus the
 * simple content resources (Notices, News, Events, Downloads, Other Pages).
 * Previously split across per-area clusters and a separate "Website
 * Management" group; merged into one so there's exactly one place to look.
 */
class WebsitePagesCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Website Pages';

    protected static ?int $navigationSort = 1;
}
