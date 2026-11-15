<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('admin.departments.index', [
            'departments' => Department::with('faculty')->withCount('studyPrograms')->orderBy('code')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.departments.form', ['item' => null, 'faculties' => Faculty::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Department::create($this->validated($request));

        return redirect()->route('admin.departments.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.form', ['item' => $department, 'faculties' => Faculty::orderBy('name')->get()]);
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $department->update($this->validated($request, $department));

        return redirect()->route('admin.departments.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        if ($department->studyPrograms()->exists()) {
            return back()->with('error', 'Jurusan masih memiliki program studi. Hapus/pindahkan program studinya dulu.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Jurusan berhasil dihapus.');
    }

    private function validated(Request $request, ?Department $department = null): array
    {
        $data = $request->validate([
            'faculty_id' => ['nullable', 'exists:faculties,id'], // boleh kosong: tidak semua jurusan punya fakultas
            'code' => ['required', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($department)],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $data['faculty_id'] = $data['faculty_id'] ?: null;

        return $data;
    }
}
