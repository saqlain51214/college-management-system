<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionInquiry extends Model
{
    protected $fillable = [
        'reference_no','name','father_name','email','phone','student_phone',
        'entry_path','gender','campus','city','cnic','dob','address','program_id',
        'qualification','academic_details','declare_true','message','status','admin_notes',
    ];

    protected $casts = [
        'dob' => 'date',
        'academic_details' => 'array',
        'declare_true' => 'boolean',
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
