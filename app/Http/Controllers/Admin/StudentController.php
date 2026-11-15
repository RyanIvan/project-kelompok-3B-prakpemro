<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['user', 'studyProgram'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('nim', 'like', $keyword)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $keyword)->orWhere('email', 'like', $keyword));
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('study_program_id'), fn ($q) => $q->where('study_program_id', $request->integer('study_program_id')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'programs' => StudyProgram::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.students.form', ['item' => null, 'programs' => StudyProgram::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $program = StudyProgram::findOrFail($data['study_program_id']);

        DB::transaction(function () use ($data, $program) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => ($data['password'] ?? null) ?: $data['nim'], // default: NIM (di-hash otomatis oleh cast)
                'role' => User::ROLE_STUDENT,
            ]);

            $user->student()->create([
                'nim' => $data['nim'],
                'batch' => $data['batch'],
                'study_program_id' => $program->id,
                'study_program' => $program->full_name,
                'status' => $data['status'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        return view('admin.students.form', [
            'item' => $student->load('user'),
            'programs' => StudyProgram::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validated($request, $student);
        $program = StudyProgram::findOrFail($data['study_program_id']);

        DB::transaction(function () use ($data, $program, $student) {
            $userData = ['name' => $data['name'], 'email' => $data['email']];
            if (! empty($data['password'])) {
                $userData['password'] = $data['password'];
            }
            $student->user->update($userData);

            $student->update([
                'nim' => $data['nim'],
                'batch' => $data['batch'],
                'study_program_id' => $program->id,
                'study_program' => $program->full_name,
                'status' => $data['status'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /** Ubah cepat status Aktif <-> Non-Aktif dari tabel. */
    public function toggleStatus(Student $student): RedirectResponse
    {
        $student->update([
            'status' => $student->status === Student::STATUS_ACTIVE ? Student::STATUS_INACTIVE : Student::STATUS_ACTIVE,
        ]);

        return back()->with('success', "Status {$student->user->name} sekarang {$student->status}.");
    }

    public function destroy(Student $student): RedirectResponse
    {
        // Menghapus akun user otomatis menghapus profil mahasiswa + KRS-nya (cascade).
        $student->user->delete();

        return redirect()->route('admin.students.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }

    private function validated(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student?->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
            'nim' => ['required', 'string', 'max:30', Rule::unique('students', 'nim')->ignore($student)],
            'batch' => ['required', 'integer', 'min:2000', 'max:2100'],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'status' => ['required', Rule::in([Student::STATUS_ACTIVE, Student::STATUS_INACTIVE])],
        ]);
    }
}
