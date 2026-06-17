<?php

namespace App\Filament\Resources\AcademicProgramResource\Pages;

use App\Filament\Resources\AcademicProgramResource;
use App\Services\AcademicProgramService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;

class EditAcademicProgram extends EditRecord
{
    protected static string $resource = AcademicProgramResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(AcademicProgramService::class)->updateModel($record, $data);
        } catch (UniqueConstraintViolationException $e) {
            $msg = $e->getMessage();

            $body = match(true) {
                str_contains($msg, '_slug_') => 'Another program with this slug already exists.',
                str_contains($msg, '_code_') => 'This program code is already in use.',
                str_contains($msg, '_name_') => 'Another program with this name already exists.',
                default                      => 'A duplicate entry was detected.',
            };

            Notification::make()
                ->title('Duplicate Entry — Cannot Save')
                ->body($body)
                ->danger()
                ->persistent()
                ->send();

            $this->halt();

            return $record;
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
