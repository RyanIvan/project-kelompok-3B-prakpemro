<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = CourseClass::with(['course', 'lecturer.user', 'studyProgram'])
            ->withCount([
                'enrollments as enrolled_count' => fn ($q) => $q->where('status', '!=', 'Rejected'),
            ])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->whereHas('course', fn ($q) => $q->where('name', 'like', $keyword)->orWhere('code', 'like', $keyword));
            })
            ->when($request->filled('academic_year'), fn ($q) => $q->where('academic_year', $request->input('academic_year')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.classes.index', [
            'classes' => $classes,
            'academicYears' => CourseClass::select('academic_year')->distinct()->orderByDesc('academic_year')->pluck('academic_year'),
        ]);
    }

    public function create()
    {
        return view('admin.classes.form', ['item' => null] + $this->options());
    }

    public function store(Request $request): RedirectResponse
    {
        CourseClass::create($this->validated($request));

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(CourseClass $courseClass)
    {
        return view('admin.classes.form', ['item' => $courseClass] + $this->options());
    }

    public function update(Request $request, CourseClass $courseClass): RedirectResponse
    {
        $courseClass->update($this->validated($request, $courseClass));

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(CourseClass $courseClass): RedirectResponse
    {
        if ($courseClass->enrollments()->exists()) {
            return back()->with('error', 'Kelas sudah dipilih mahasiswa (KRS), tidak bisa dihapus.');
        }

        $courseClass->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dihapus.');
    }

    private function options(): array
    {
        return [
            'courses' => Course::orderBy('code')->get(),
            'lecturers' => Lecturer::with('user')->get()->sortBy('user.name'),
            'programs' => StudyProgram::orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, ?CourseClass $class = null): array
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'lecturer_id' => ['required', 'exists:lecturers,id'],
            'study_program_id' => ['nullable', 'exists:study_programs,id'],
            'name' => [
                'required', 'string', 'max:10',
                // Kombinasi mata kuliah + nama kelas + tahun ajaran harus unik.
                Rule::unique('classes', 'name')
                    ->where('course_id', $request->input('course_id'))
                    ->where('academic_year', $request->input('academic_year'))
                    ->ignore($class),
            ],
            'academic_year' => ['required', 'string', 'max:50'], // contoh: 2025/2026 Genap
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $data['study_program_id'] = $data['study_program_id'] ?: null;

        return $data;
    }
}
