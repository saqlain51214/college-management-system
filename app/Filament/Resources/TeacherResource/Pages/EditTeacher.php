<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Services\TeacherService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(TeacherService::class)->updateTeacher($record, $data);
        } catch (UniqueConstraintViolationException $e) {
            $msg  = $e->getMessage();
            $body = match(true) {
                str_contains($msg, '_cnic_')  => 'A teacher with this CNIC is already registered.',
                str_contains($msg, '_email_') => 'This email is already in use.',
                default                       => 'A duplicate entry was detected.',
            };
            Notification::make()->title('Duplicate — Cannot Save')->body($body)->danger()->persistent()->send();
            $this->halt();
            return $record;
        }
    }

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make(), Actions\RestoreAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
