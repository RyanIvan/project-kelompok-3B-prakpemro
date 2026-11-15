<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer) {
            abort(403, 'Profil dosen tidak ditemukan untuk akun ini.');
        }

        $classes = $lecturer->classes()
            ->with(['course', 'studyProgram', 'schedules.room'])
            ->withCount([
                'enrollments as approved_count' => fn ($q) => $q->where('status', 'Approved'),
                'enrollments as pending_count' => fn ($q) => $q->where('status', 'Pending'),
                'enrollments as rejected_count' => fn ($q) => $q->where('status', 'Rejected'),
            ])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->whereHas('course', fn ($q) => $q->where('name', 'like', $keyword)->orWhere('code', 'like', $keyword));
            })
            ->when($request->filled('academic_year'), fn ($q) => $q->where('academic_year', $request->input('academic_year')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $academicYears = CourseClass::where('lecturer_id', $lecturer->id)
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('dosen.classes.index', compact('classes', 'academicYears'));
    }

    public function show(Request $request, CourseClass $courseClass)
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer || $courseClass->lecturer_id !== $lecturer->id) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $courseClass->load(['course', 'studyProgram', 'schedules.room']);

        $enrollments = $courseClass->enrollments()
            ->with(['student.user', 'student.studyProgram', 'grade'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->whereHas('student', function ($s) use ($keyword) {
                    $s->where('nim', 'like', $keyword)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $keyword));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = $courseClass->enrollments()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('dosen.classes.show', compact('courseClass', 'enrollments', 'statusCounts'));
    }

    public function updateEnrollmentStatus(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer || $enrollment->courseClass->lecturer_id !== $lecturer->id) {
            abort(403, 'Anda tidak memiliki izin memvalidasi mahasiswa ini.');
        }

        $data = $request->validate([
            'status' => ['required', 'in:Pending,Approved,Rejected'],
        ]);

        $enrollment->update(['status' => $data['status']]);

        $statusText = match ($data['status']) {
            'Approved' => 'disetujui',
            'Rejected' => 'ditolak',
            default => 'diubah menjadi pending',
        };

        return back()->with('success', "Status KRS mahasiswa {$enrollment->student->user->name} berhasil {$statusText}.");
    }
}
