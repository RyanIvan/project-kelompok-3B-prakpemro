<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id', 'course_id', 'assignment_score', 'mid_score',
        'final_score', 'final_grade', 'grade_letter',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Hitung nilai akhir berdasarkan bobot: Tugas 30%, UTS 35%, UAS 35%.
     */
    public static function calculateFinalGrade(?float $assignment, ?float $mid, ?float $final): ?float
    {
        if ($assignment === null && $mid === null && $final === null) {
            return null;
        }

        $a = $assignment ?? 0;
        $m = $mid ?? 0;
        $f = $final ?? 0;

        return round(($a * 0.30) + ($m * 0.35) + ($f * 0.35), 2);
    }

    /**
     * Konversi nilai akhir ke huruf mutu standar perguruan tinggi.
     */
    public static function calculateGradeLetter(?float $finalGrade): ?string
    {
        if ($finalGrade === null) {
            return null;
        }

        return match (true) {
            $finalGrade >= 85 => 'A',
            $finalGrade >= 80 => 'A-',
            $finalGrade >= 75 => 'B+',
            $finalGrade >= 70 => 'B',
            $finalGrade >= 65 => 'C+',
            $finalGrade >= 60 => 'C',
            $finalGrade >= 50 => 'D',
            default => 'E',
        };
    }
}
