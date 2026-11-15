<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\Grade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer) {
            abort(403, 'Profil dosen tidak ditemukan untuk akun ini.');
        }

        $classes = $lecturer->classes()
            ->with(['course', 'studyProgram'])
            ->withCount([
                'enrollments as approved_students_count' => fn ($q) => $q->where('status', 'Approved'),
                'enrollments as graded_students_count' => fn ($q) => $q->where('status', 'Approved')
                    ->whereHas('grade', fn ($g) => $g->whereNotNull('final_grade')),
            ])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->whereHas('course', fn ($q) => $q->where('name', 'like', $keyword)->orWhere('code', 'like', $keyword));
            })
            ->when($request->filled('academic_year'), fn ($q) => $q->where('academic_year', $request->input('academic_year')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $academicYears = CourseClass::where('lecturer_id', $lecturer->id)
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('dosen.grades.index', compact('classes', 'academicYears'));
    }

    public function edit(Request $request, CourseClass $courseClass)
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer || $courseClass->lecturer_id !== $lecturer->id) {
            abort(403, 'Anda tidak memiliki akses penilaian pada kelas ini.');
        }

        $courseClass->load(['course', 'studyProgram']);

        $enrollments = $courseClass->enrollments()
            ->where('status', 'Approved')
            ->with(['student.user', 'grade'])
            ->get()
            ->sortBy('student.nim');

        return view('dosen.grades.edit', compact('courseClass', 'enrollments'));
    }

    public function update(Request $request, CourseClass $courseClass): RedirectResponse
    {
        $lecturer = $request->user()->lecturer;

        if (! $lecturer || $courseClass->lecturer_id !== $lecturer->id) {
            abort(403, 'Anda tidak memiliki akses penilaian pada kelas ini.');
        }

        $data = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*.assignment_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.mid_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.final_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $approvedEnrollments = $courseClass->enrollments()
            ->where('status', 'Approved')
            ->get()
            ->keyBy('id');

        foreach ($data['grades'] as $enrollmentId => $scores) {
            if (! $approvedEnrollments->has($enrollmentId)) {
                continue;
            }

            $assignment = isset($scores['assignment_score']) && $scores['assignment_score'] !== '' ? (float) $scores['assignment_score'] : null;
            $mid = isset($scores['mid_score']) && $scores['mid_score'] !== '' ? (float) $scores['mid_score'] : null;
            $final = isset($scores['final_score']) && $scores['final_score'] !== '' ? (float) $scores['final_score'] : null;

            $finalGrade = Grade::calculateFinalGrade($assignment, $mid, $final);
            $gradeLetter = Grade::calculateGradeLetter($finalGrade);

            Grade::updateOrCreate(
                ['enrollment_id' => $enrollmentId],
                [
                    'course_id' => $courseClass->course_id,
                    'assignment_score' => $assignment,
                    'mid_score' => $mid,
                    'final_score' => $final,
                    'final_grade' => $finalGrade,
                    'grade_letter' => $gradeLetter,
                ]
            );
        }

        return redirect()->route('dosen.grades.edit', $courseClass)
            ->with('success', 'Nilai mahasiswa berhasil disimpan.');
    }
}
