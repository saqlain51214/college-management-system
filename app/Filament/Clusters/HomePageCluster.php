<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class HomePageCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Home Page';

    protected static ?int $navigationSort = 2;
}
