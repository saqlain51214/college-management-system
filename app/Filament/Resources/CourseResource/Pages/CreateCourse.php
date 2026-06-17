<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(CourseService::class)->createModel($data);
        } catch (UniqueConstraintViolationException $e) {
            $msg  = $e->getMessage();
            $body = match(true) {
                str_contains($msg, '_code_') => 'This course code is already in use.',
                str_contains($msg, '_slug_') => 'A course with this name (slug) already exists.',
                default                      => 'A duplicate entry was detected. Check Code and Name.',
            };
            Notification::make()->title('Duplicate — Cannot Save')->body($body)->danger()->persistent()->send();
            $this->halt();
            return new Course();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
