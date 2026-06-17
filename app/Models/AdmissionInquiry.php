<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionInquiry extends Model
{
    protected $fillable = [
        'name','father_name','email','phone','program_id',
        'qualification','message','status','admin_notes',
    ];

    public function program(): BelongsTo { return $this->belongsTo(AcademicProgram::class, 'program_id'); }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new'       => 'info',
            'contacted' => 'warning',
            'enrolled'  => 'success',
            'rejected'  => 'danger',
            default     => 'gray',
        };
    }
}
