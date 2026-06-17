<?php

namespace App\Filament\Resources\LmsMaterialResource\Pages;

use App\Filament\Resources\LmsMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLmsMaterial extends CreateRecord
{
    protected static string $resource = LmsMaterialResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
