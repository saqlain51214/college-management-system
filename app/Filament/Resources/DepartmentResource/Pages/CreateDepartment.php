<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use App\Models\Department;
use App\Services\DepartmentService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(DepartmentService::class)->createModel($data);
        } catch (UniqueConstraintViolationException $e) {
            $msg = $e->getMessage();

            $body = match(true) {
                str_contains($msg, '_slug_')  => 'A department with this name (slug) already exists. Change the department name or edit the URL slug field.',
                str_contains($msg, '_code_')  => 'This department code is already in use. Please enter a different code.',
                str_contains($msg, '_name_')  => 'A department with this exact name already exists.',
                default                       => 'A duplicate entry was detected. Please check Name, Slug, and Code fields.',
            };

            Notification::make()
                ->title('Duplicate Entry — Cannot Save')
                ->body($body)
                ->danger()
                ->persistent()
                ->send();

            $this->halt();

            return new Department(); // unreachable — satisfies return type
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
