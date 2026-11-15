<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function index()
    {
        return view('admin.faculties.index', [
            'faculties' => Faculty::withCount('departments')->orderBy('code')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.faculties.form', ['item' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        Faculty::create($this->validated($request));

        return redirect()->route('admin.faculties.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Faculty $faculty)
    {
        return view('admin.faculties.form', ['item' => $faculty]);
    }

    public function update(Request $request, Faculty $faculty): RedirectResponse
    {
        $faculty->update($this->validated($request, $faculty));

        return redirect()->route('admin.faculties.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Faculty $faculty): RedirectResponse
    {
        // Jurusan di bawahnya tidak dihapus, hanya menjadi "tanpa fakultas" (nullOnDelete).
        $faculty->delete();

        return redirect()->route('admin.faculties.index')->with('success', 'Fakultas berhasil dihapus.');
    }

    private function validated(Request $request, ?Faculty $faculty = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('faculties', 'code')->ignore($faculty)],
            'name' => ['required', 'string', 'max:255'],
        ]);
    }
}
