<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeStructureRevision extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'fee_structure_id', 'old_amount', 'new_amount', 'effective_from', 'reason', 'changed_by',
    ];

    protected $casts = [
        'old_amount'     => 'decimal:2',
        'new_amount'     => 'decimal:2',
        'effective_from' => 'date',
        'created_at'     => 'datetime',
    ];

    public function feeStructure(): BelongsTo { return $this->belongsTo(FeeStructure::class); }
    public function changedBy(): BelongsTo    { return $this->belongsTo(User::class, 'changed_by'); }
}
