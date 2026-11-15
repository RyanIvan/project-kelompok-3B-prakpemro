<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model untuk tabel "classes" (kelas perkuliahan).
 * Tidak dinamai "Class" karena itu kata cadangan di PHP.
 */
class CourseClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['course_id', 'lecturer_id', 'study_program_id', 'name', 'academic_year', 'capacity'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    /** Contoh: "Pemrograman Web - Kelas A" */
    public function getLabelAttribute(): string
    {
        return ($this->course?->name ?? '-').' - Kelas '.$this->name;
    }
}
