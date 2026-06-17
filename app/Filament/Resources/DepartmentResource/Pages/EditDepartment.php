<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;

class EditDepartment extends EditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(DepartmentService::class)->updateModel($record, $data);
        } catch (UniqueConstraintViolationException $e) {
            $msg = $e->getMessage();

            $body = match(true) {
                str_contains($msg, '_slug_') => 'Another department already has this slug. Change the name or edit the URL slug.',
                str_contains($msg, '_code_') => 'This department code is already in use by another department.',
                str_contains($msg, '_name_') => 'Another department with this exact name already exists.',
                default                      => 'A duplicate entry was detected. Check Name, Slug, and Code fields.',
            };

            Notification::make()
                ->title('Duplicate Entry — Cannot Save')
                ->body($body)
                ->danger()
                ->persistent()
                ->send();

            $this->halt();

            return $record; // unreachable — satisfies return type
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
