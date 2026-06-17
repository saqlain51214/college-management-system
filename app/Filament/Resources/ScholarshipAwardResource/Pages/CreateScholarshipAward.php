<?php

namespace App\Filament\Resources\ScholarshipAwardResource\Pages;

use App\Filament\Resources\ScholarshipAwardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScholarshipAward extends CreateRecord
{
    protected static string $resource = ScholarshipAwardResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
