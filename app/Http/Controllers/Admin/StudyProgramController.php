<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudyProgramController extends Controller
{
    public function index()
    {
        return view('admin.study-programs.index', [
            'programs' => StudyProgram::with('department.faculty')
                ->withCount(['students', 'courses'])
                ->orderBy('code')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.study-programs.form', ['item' => null, 'departments' => Department::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        StudyProgram::create($this->validated($request));

        return redirect()->route('admin.study-programs.index')->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(StudyProgram $studyProgram)
    {
        return view('admin.study-programs.form', ['item' => $studyProgram, 'departments' => Department::orderBy('name')->get()]);
    }

    public function update(Request $request, StudyProgram $studyProgram): RedirectResponse
    {
        $studyProgram->update($this->validated($request, $studyProgram));

        // Jaga kolom ringkas students.study_program tetap sinkron dengan nama prodi.
        $studyProgram->students()->update(['study_program' => $studyProgram->full_name]);

        return redirect()->route('admin.study-programs.index')->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy(StudyProgram $studyProgram): RedirectResponse
    {
        if ($studyProgram->students()->exists()) {
            return back()->with('error', 'Program studi masih dipakai mahasiswa, tidak bisa dihapus.');
        }

        $studyProgram->delete();

        return redirect()->route('admin.study-programs.index')->with('success', 'Program studi berhasil dihapus.');
    }

    private function validated(Request $request, ?StudyProgram $program = null): array
    {
        return $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'code' => ['required', 'string', 'max:20', Rule::unique('study_programs', 'code')->ignore($program)],
            'name' => ['required', 'string', 'max:255'],
            'degree' => ['required', Rule::in(StudyProgram::DEGREES)],
        ]);
    }
}
