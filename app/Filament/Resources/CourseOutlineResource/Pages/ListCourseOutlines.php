<?php
namespace App\Filament\Resources\CourseOutlineResource\Pages;
use App\Filament\Resources\CourseOutlineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListCourseOutlines extends ListRecords
{
    protected static string $resource = CourseOutlineResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
