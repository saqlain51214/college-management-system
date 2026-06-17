<?php

namespace App\Filament\Resources\BookIssueResource\Pages;

use App\Filament\Resources\BookIssueResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookIssue extends CreateRecord
{
    protected static string $resource = BookIssueResource::class;
    protected function getRedirectUrl(): string { return $this->getResource()::getUrl('index'); }
}
