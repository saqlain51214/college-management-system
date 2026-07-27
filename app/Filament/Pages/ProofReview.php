<?php

namespace App\Filament\Pages;

use App\Enums\PaymentStatusEnum;
use App\Filament\Resources\FeePaymentResource;
use App\Models\FeePayment;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ProofReview extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-paper-clip';
    protected static ?string $navigationGroup = 'Fees & Billing';
    protected static ?string $navigationLabel = 'Proof Review';
    protected static ?string $title = 'Payment Proof Review';
    protected static ?int    $navigationSort  = 5;

    protected static string $view = 'filament.pages.proof-review';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'Developer', 'panel_user']) ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $count = static::pendingQuery()->count();
            return $count ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string { return 'danger'; }

    protected static function pendingQuery()
    {
        return FeePayment::whereNotNull('payment_proof_path')
            ->where('payment_status', '!=', PaymentStatusEnum::Paid->value);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => static::pendingQuery())
            ->columns([
                Tables\Columns\TextColumn::make('student.roll_number')->label('Roll No.')->searchable(),
                Tables\Columns\TextColumn::make('student.name')->label('Student')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('challan_number')->label('Challan')->searchable(),
                Tables\Columns\TextColumn::make('amount_due')->label('Challan Amount')->money('PKR'),
                Tables\Columns\TextColumn::make('proof_claimed_amount')->label('Claimed Amount')->money('PKR')->weight('bold'),
                Tables\Columns\TextColumn::make('proof_claimed_date')->label('Claimed Date')->date('d M Y'),
                Tables\Columns\TextColumn::make('proof_uploaded_at')->label('Uploaded')->dateTime('d M Y, h:i A')->sortable(),
            ])
            ->actions(FeePaymentResource::proofReviewActions())
            ->emptyStateHeading('No proofs awaiting review')
            ->emptyStateDescription('Every uploaded payment proof has been confirmed or rejected.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->defaultSort('proof_uploaded_at', 'asc')
            ->striped();
    }
}
