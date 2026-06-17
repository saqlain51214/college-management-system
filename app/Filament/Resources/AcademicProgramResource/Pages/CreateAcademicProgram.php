<?php

namespace App\Filament\Resources\AcademicProgramResource\Pages;

use App\Filament\Resources\AcademicProgramResource;
use App\Models\AcademicProgram;
use App\Services\AcademicProgramService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;

class CreateAcademicProgram extends CreateRecord
{
    protected static string $resource = AcademicProgramResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return app(AcademicProgramService::class)->createModel($data);
        } catch (UniqueConstraintViolationException $e) {
            $msg = $e->getMessage();

            $body = match(true) {
                str_contains($msg, '_slug_') => 'A program with this name (slug) already exists.',
                str_contains($msg, '_code_') => 'This program code is already in use.',
                str_contains($msg, '_name_') => 'A program with this exact name already exists.',
                default                      => 'A duplicate entry was detected. Check Name, Slug, and Code.',
            };

            Notification::make()
                ->title('Duplicate Entry — Cannot Save')
                ->body($body)
                ->danger()
                ->persistent()
                ->send();

            $this->halt();

            return new AcademicProgram();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
