<?php

namespace App\Filament\Resources\TeacherSalaryPaymentResource\Pages;

use App\Filament\Resources\TeacherSalaryPaymentResource;
use App\Models\Teacher;
use App\Models\TeacherSalaryPayment;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;

class ListTeacherSalaryPayments extends ListRecords
{
    protected static string $resource = TeacherSalaryPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateMonthlySalaries')
                ->label('Generate Monthly Salaries')
                ->icon('heroicon-o-document-plus')
                ->color('success')
                ->modalHeading('Generate Salaries for a Month')
                ->modalDescription('Creates a salary payment record for every active teacher for the selected month, using each teacher\'s Basic Salary from their profile. Teachers who already have a salary record for that month are skipped — running this again for the same month is always safe.')
                ->modalSubmitActionLabel('Generate')
                ->form([
                    Forms\Components\Select::make('month')
                        ->options(collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => Carbon::create(2000, $m, 1)->format('F')])->all())
                        ->default(now()->month)
                        ->required(),
                    Forms\Components\TextInput::make('year')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $teachers = Teacher::where('is_active', true)->get();
                    $created = 0;
                    $skipped = 0;

                    foreach ($teachers as $teacher) {
                        $existing = TeacherSalaryPayment::withTrashed()
                            ->where('teacher_id', $teacher->id)
                            ->where('year', $data['year'])
                            ->where('month', $data['month'])
                            ->exists();

                        if ($existing) {
                            $skipped++;
                            continue;
                        }

                        TeacherSalaryPayment::generateForMonth($teacher, (int) $data['year'], (int) $data['month']);
                        $created++;
                    }

                    Notification::make()
                        ->title("Generated {$created} salary payment(s), skipped {$skipped} (already existed)")
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make(),
        ];
    }
}
