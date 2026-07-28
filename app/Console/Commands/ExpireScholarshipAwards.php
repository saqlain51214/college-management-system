<?php

namespace App\Console\Commands;

use App\Enums\ScholarshipStatusEnum;
use App\Models\ScholarshipAward;
use App\Support\ActivityLogWriter;
use Illuminate\Console\Command;

/**
 * Scholarships can be time-bound (a single semester, one academic year) via
 * each award's expiry_date — this is what actually enforces that: once the
 * date passes, the award flips to Expired, which (via ScholarshipAward's own
 * model event) clears the student's scholarship fields unless another award
 * is still active, so future challans stop getting a discount that no
 * longer applies. Past challans keep their own snapshot either way.
 */
class ExpireScholarshipAwards extends Command
{
    protected $signature = 'scholarships:expire-awards';
    protected $description = 'Expire scholarship awards past their expiry date and revert the student scholarship fields if nothing else is still active';

    public function handle(): int
    {
        $expired = ScholarshipAward::query()
            ->whereIn('status', [ScholarshipStatusEnum::Approved, ScholarshipStatusEnum::Disbursed])
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now()->toDateString())
            ->with('student')
            ->get();

        if ($expired->isEmpty()) {
            $this->info('No scholarship awards to expire.');
            return self::SUCCESS;
        }

        foreach ($expired as $award) {
            $award->update(['status' => ScholarshipStatusEnum::Expired]);

            ActivityLogWriter::activity(
                'scholarship.award_expired',
                subject: $award->student,
                message: $award->student
                    ? "Scholarship award #{$award->id} expired (was {$award->expiry_date?->format('d M Y')})."
                    : "Scholarship award #{$award->id} expired.",
                meta: ['award_id' => $award->id, 'scholarship_id' => $award->scholarship_id],
            );
        }

        $this->info("Expired {$expired->count()} scholarship award(s).");

        return self::SUCCESS;
    }
}
