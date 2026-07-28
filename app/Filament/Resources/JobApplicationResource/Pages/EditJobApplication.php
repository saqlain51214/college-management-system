<?php

namespace App\Filament\Resources\JobApplicationResource\Pages;

use App\Filament\Resources\JobApplicationResource;
use App\Mail\JobApplicationStatusMail;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditJobApplication extends EditRecord
{
    protected static string $resource = JobApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        if ($record->wasChanged('status')) {
            Mail::to($record->email)->queue(new JobApplicationStatusMail($record));

            Notification::make()
                ->title('Applicant notified')
                ->body('An email was sent to ' . $record->email . ' about this status change.')
                ->success()->send();
        }
    }
}
