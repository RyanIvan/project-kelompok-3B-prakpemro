<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer) {
            abort(403, 'Profil dosen tidak ditemukan untuk akun ini.');
        }

        $classIdsQuery = $lecturer->classes();

        if ($request->filled('academic_year')) {
            $classIdsQuery->where('academic_year', $request->input('academic_year'));
        }

        $classIds = $classIdsQuery->pluck('id');

        $schedulesQuery = Schedule::with(['courseClass.course', 'courseClass.studyProgram', 'room'])
            ->whereIn('class_id', $classIds);

        if ($request->filled('day')) {
            $schedulesQuery->where('day', $request->input('day'));
        }

        $daysOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6];

        $schedules = $schedulesQuery->get()->sort(function ($a, $b) use ($daysOrder) {
            $dayOrderA = $daysOrder[$a->day] ?? 99;
            $dayOrderB = $daysOrder[$b->day] ?? 99;

            if ($dayOrderA === $dayOrderB) {
                return strcmp($a->start_time, $b->start_time);
            }

            return $dayOrderA <=> $dayOrderB;
        });

        $academicYears = CourseClass::where('lecturer_id', $lecturer->id)
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('dosen.schedules.index', [
            'schedules' => $schedules,
            'academicYears' => $academicYears,
            'days' => array_keys($daysOrder),
        ]);
    }
}
