<?php

namespace App\Filament\Resources\AttendanceSessionResource\Pages;

use App\Filament\Resources\AttendanceSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceSession extends CreateRecord
{
    protected static string $resource = AttendanceSessionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
