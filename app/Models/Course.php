<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'sks', 'semester', 'study_program_id'];

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    /** Mata kuliah yang harus ditempuh lebih dulu. */
    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'course_prerequisites', 'course_id', 'prerequisite_course_id')
            ->withTimestamps();
    }

    public function classes(): HasMany
    {
        return $this->hasMany(CourseClass::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
