<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer) {
            abort(403, 'Profil dosen tidak ditemukan untuk akun ini.');
        }

        $classIds = $lecturer->classes()->pluck('id');

        $dayNames = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];
        $today = $dayNames[now()->dayOfWeekIso] ?? 'Senin';

        $stats = [
            'total_classes' => $classIds->count(),
            'total_students' => Enrollment::whereIn('class_id', $classIds)
                ->where('status', 'Approved')
                ->distinct('student_id')
                ->count('student_id'),
            'pending_krs' => Enrollment::whereIn('class_id', $classIds)
                ->where('status', 'Pending')
                ->count(),
            'today_schedules_count' => Schedule::whereIn('class_id', $classIds)
                ->where('day', $today)
                ->count(),
        ];

        $todaySchedules = Schedule::with(['courseClass.course', 'courseClass.studyProgram', 'room'])
            ->whereIn('class_id', $classIds)
            ->where('day', $today)
            ->orderBy('start_time')
            ->get();

        $classes = $lecturer->classes()
            ->with(['course', 'studyProgram'])
            ->withCount([
                'enrollments as approved_count' => fn ($q) => $q->where('status', 'Approved'),
                'enrollments as pending_count' => fn ($q) => $q->where('status', 'Pending'),
            ])
            ->latest()
            ->take(6)
            ->get();

        return view('dosen.dashboard', compact('lecturer', 'stats', 'today', 'todaySchedules', 'classes'));
    }
}
