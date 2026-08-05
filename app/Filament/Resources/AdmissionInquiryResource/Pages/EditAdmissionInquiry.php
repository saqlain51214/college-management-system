<?php
namespace App\Filament\Resources\AdmissionInquiryResource\Pages;
use App\Filament\Resources\AdmissionInquiryResource;
use App\Mail\AdmissionInquiryStatusMail;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditAdmissionInquiry extends EditRecord {
    protected static string $resource = AdmissionInquiryResource::class;

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        // "rejected" has its own dedicated action/mail with admin_notes already
        // captured at that point — avoid double-emailing on a plain form save.
        if ($record->wasChanged('status') && in_array($record->status, ['contacted', 'enrolled'], true) && filled($record->email)) {
            Mail::to($record->email)->queue(new AdmissionInquiryStatusMail($record));
        }
    }
}
