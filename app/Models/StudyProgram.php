<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgram extends Model
{
    public const DEGREES = ['D3', 'D4', 'S1', 'S2', 'S3'];

    protected $fillable = ['department_id', 'code', 'name', 'degree'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(CourseClass::class);
    }

    /** Contoh: "S1 Informatika" */
    public function getFullNameAttribute(): string
    {
        return $this->degree.' '.$this->name;
    }
}
