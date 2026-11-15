<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Enrollment;
use Illuminate\Http\Request;

/**
 * Monitoring KRS untuk admin (read-only).
 * Validasi KRS dilakukan oleh dosen, bukan admin.
 */
class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = Enrollment::with(['student.user', 'courseClass.course', 'courseClass.lecturer.user'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->whereHas('student', function ($s) use ($keyword) {
                    $s->where('nim', 'like', $keyword)->orWhereHas('user', fn ($u) => $u->where('name', 'like', $keyword));
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('academic_year'), fn ($q) => $q->where('academic_year', $request->input('academic_year')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.enrollments.index', [
            'enrollments' => $enrollments,
            'counts' => Enrollment::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'academicYears' => CourseClass::select('academic_year')->distinct()->orderByDesc('academic_year')->pluck('academic_year'),
        ]);
    }
}
