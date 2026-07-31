<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class AcademicsCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Academics';

    protected static ?int $navigationSort = 1;
}
