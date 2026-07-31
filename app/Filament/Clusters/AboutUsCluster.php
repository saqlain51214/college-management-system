<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class AboutUsCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationLabel = 'About Us';

    protected static ?int $navigationSort = 3;
}
