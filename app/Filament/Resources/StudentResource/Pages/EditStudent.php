<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Services\StudentService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(StudentService::class)->updateStudent($record, $data);
        } catch (UniqueConstraintViolationException $e) {
            $msg  = $e->getMessage();
            $body = match(true) {
                str_contains($msg, '_cnic_')  => 'A student with this CNIC is already registered.',
                str_contains($msg, '_email_') => 'This email address is already in use.',
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
