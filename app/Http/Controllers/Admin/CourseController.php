<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['studyProgram', 'prerequisites'])
            ->withCount('classes')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('code', 'like', $keyword)->orWhere('name', 'like', $keyword));
            })
            ->when($request->filled('study_program_id'), fn ($q) => $q->where('study_program_id', $request->integer('study_program_id')))
            ->when($request->filled('semester'), fn ($q) => $q->where('semester', $request->integer('semester')))
            ->orderBy('semester')
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('admin.courses.index', [
            'courses' => $courses,
            'programs' => StudyProgram::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.courses.form', [
            'item' => null,
            'programs' => StudyProgram::orderBy('name')->get(),
            'otherCourses' => Course::orderBy('code')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $course = Course::create(collect($data)->except('prerequisites')->all());
        $course->prerequisites()->sync($data['prerequisites'] ?? []);

        return redirect()->route('admin.courses.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.form', [
            'item' => $course->load('prerequisites'),
            'programs' => StudyProgram::orderBy('name')->get(),
            'otherCourses' => Course::where('id', '!=', $course->id)->orderBy('code')->get(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = $this->validated($request, $course);

        $course->update(collect($data)->except('prerequisites')->all());
        $course->prerequisites()->sync($data['prerequisites'] ?? []);

        return redirect()->route('admin.courses.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->classes()->exists()) {
            return back()->with('error', 'Mata kuliah sudah punya kelas. Hapus kelasnya terlebih dahulu.');
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('courses', 'code')->ignore($course)],
            'name' => ['required', 'string', 'max:255'],
            'sks' => ['required', 'integer', 'min:1', 'max:8'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'study_program_id' => ['nullable', 'exists:study_programs,id'],
            'prerequisites' => ['nullable', 'array'],
            'prerequisites.*' => array_filter([
                'integer',
                'exists:courses,id',
                $course ? Rule::notIn([$course->id]) : null, // mata kuliah tidak boleh jadi prasyarat dirinya sendiri
            ]),
        ]);
    }
}
