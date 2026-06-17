<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Services\CourseService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return app(CourseService::class)->updateModel($record, $data);
        } catch (UniqueConstraintViolationException $e) {
            $msg  = $e->getMessage();
            $body = match(true) {
                str_contains($msg, '_code_') => 'This course code is already in use.',
                str_contains($msg, '_slug_') => 'A course with this name already exists.',
                default                      => 'A duplicate entry was detected.',
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
