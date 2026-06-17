<?php
namespace App\Filament\Resources\ListItemResource\Pages;
use App\Filament\Resources\ListItemResource;
use App\Models\ListItem;
use Filament\Resources\Pages\CreateRecord;
class CreateListItem extends CreateRecord
{
    protected static string $resource = ListItemResource::class;
    protected function afterCreate(): void { ListItem::clearCache($this->record->category); }
}
