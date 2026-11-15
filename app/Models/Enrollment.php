<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    public const STATUSES = ['Pending', 'Approved', 'Rejected'];

    protected $fillable = ['student_id', 'class_id', 'status', 'academic_year'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function courseClass(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'class_id');
    }

    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }
}
