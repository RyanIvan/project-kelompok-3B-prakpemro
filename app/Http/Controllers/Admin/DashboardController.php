<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Enrollment;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $today = $dayNames[now()->dayOfWeekIso];

        return view('admin.dashboard', [
            'stats' => [
                'students' => Student::where('status', Student::STATUS_ACTIVE)->count(),
                'lecturers' => Lecturer::count(),
                'courses' => Course::count(),
                'classes' => CourseClass::count(),
            ],
            'pendingEnrollments' => Enrollment::where('status', 'Pending')->count(),
            'latestStudents' => Student::with(['user', 'studyProgram'])->latest()->take(5)->get(),
            'today' => $today,
            'todaySchedules' => Schedule::with(['courseClass.course', 'courseClass.lecturer.user', 'room'])
                ->where('day', $today)
                ->orderBy('start_time')
                ->get(),
        ]);
    }
}
