<?php
namespace App\Filament\Resources\ListItemResource\Pages;
use App\Filament\Resources\ListItemResource;
use App\Models\ListItem;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditListItem extends EditRecord
{
    protected static string $resource = ListItemResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
    protected function afterSave(): void { ListItem::clearCache($this->record->category); }
}
