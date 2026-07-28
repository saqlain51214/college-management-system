<?php

namespace App\Filament\Resources\FeePaymentResource\Pages;

use App\Enums\FeeTypeEnum;
use App\Filament\Resources\FeePaymentResource;
use App\Models\AcademicYear;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateFeePayment extends CreateRecord
{
    protected static string $resource = FeePaymentResource::class;

    /**
     * A student is usually billed several fee heads for the same period at
     * once (admission + tuition + lab, etc.) — checking several boxes here
     * creates one independent challan per selected type in a single submit,
     * instead of the admin repeating "Create" once per fee head.
     */
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Challan Details')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('student_id')
                        ->label('Student')
                        ->options(fn () => Student::where('is_active', true)->orderBy('name')
                            ->get()->mapWithKeys(fn ($s) => [$s->id => $s->roll_number . ' — ' . $s->name]))
                        ->searchable()->preload()->required()->live(),

                    Forms\Components\Select::make('semester_number')
                        ->label('Semester')
                        ->options(collect(range(1, 8))->mapWithKeys(fn ($n) => [$n => "Semester $n"])->all())
                        ->placeholder('N/A'),

                    Forms\Components\Select::make('academic_year_id')
                        ->label('Academic Year')
                        ->options(fn () => AcademicYear::selectOptions())
                        ->searchable(),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Due Date')->displayFormat('d M Y')->native(false)
                        ->default(now()->addDays(15)->toDateString())->required(),

                    Forms\Components\TextInput::make('transaction_id')->label('Transaction / Reference ID (Optional)')->maxLength(100),
                    Forms\Components\TextInput::make('bank_name')->label('Bank Name (Optional)')->maxLength(100),
                ]),

            Forms\Components\Section::make('Fee Heads to Bill')
                ->description('Select every fee head this challan set should cover — each becomes its own challan, and its amount auto-fills from the active Fee Structure (scholarship already applied) but can be overridden.')
                ->schema([
                    Forms\Components\CheckboxList::make('selected_fee_types')
                        ->label('Fee Types')
                        ->options(FeeTypeEnum::options())
                        ->columns(3)
                        ->live()
                        ->required()
                        ->columnSpanFull(),

                    ...collect(FeeTypeEnum::cases())->map(fn (FeeTypeEnum $type) =>
                        Forms\Components\TextInput::make("amount_{$type->value}")
                            ->label($type->label() . ' Amount (PKR)')
                            ->numeric()->prefix('Rs.')
                            ->visible(fn (Forms\Get $get) => in_array($type->value, $get('selected_fee_types') ?? []))
                            ->required(fn (Forms\Get $get) => in_array($type->value, $get('selected_fee_types') ?? []))
                            ->default(fn (Forms\Get $get) => static::suggestedAmount($get('student_id'), $type, $get('semester_number'), $get('academic_year_id')))
                    )->all(),
                ]),

            Forms\Components\Textarea::make('remarks')->label('Remarks (Optional)')->rows(2),
        ]);
    }

    protected static function suggestedAmount($studentId, FeeTypeEnum $type, $semester, $academicYearId): ?float
    {
        $student = $studentId ? Student::find($studentId) : null;
        if (! $student) {
            return null;
        }

        $summary = FeePayment::invoiceSummary($student, $type->value, $semester ? (int) $semester : null, $academicYearId ? (int) $academicYearId : null);

        return $summary['available'] > 0 ? $summary['available'] : null;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $student = Student::findOrFail($data['student_id']);
        $created = [];
        $skipped = [];

        foreach ($data['selected_fee_types'] as $feeType) {
            $amount = (float) ($data["amount_{$feeType}"] ?? 0);

            try {
                $created[] = FeePayment::generateSlip($student, [
                    'fee_type'         => $feeType,
                    'semester_number'  => $data['semester_number'] ?? null,
                    'academic_year_id' => $data['academic_year_id'] ?? null,
                    'amount'           => $amount,
                    'due_date'         => $data['due_date'] ?? null,
                    'remarks'          => $data['remarks'] ?? null,
                ]);
            } catch (\InvalidArgumentException $e) {
                $skipped[FeeTypeEnum::from($feeType)->label()] = $e->getMessage();
            }
        }

        if (empty($created)) {
            Notification::make()->title('No challans were created')
                ->body(collect($skipped)->map(fn ($msg, $type) => "{$type}: {$msg}")->implode(' '))
                ->danger()->send();

            throw new \Filament\Support\Exceptions\Halt();
        }

        $extraFields = array_filter([
            'transaction_id' => $data['transaction_id'] ?? null,
            'bank_name'      => $data['bank_name'] ?? null,
        ]);
        if ($extraFields) {
            foreach ($created as $slip) {
                $slip->update($extraFields);
            }
        }

        $summary = 'Generated ' . count($created) . ' challan(s): ' . collect($created)->map(fn ($s) => $s->challan_number)->implode(', ');
        if ($skipped) {
            $summary .= ' — skipped: ' . collect($skipped)->map(fn ($msg, $type) => "{$type} ({$msg})")->implode('; ');
        }

        Notification::make()->title('Challans Generated')->body($summary)->success()->send();

        return $created[0];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
