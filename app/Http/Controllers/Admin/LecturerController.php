<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $lecturers = Lecturer::with('user')
            ->withCount('classes')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('nip', 'like', $keyword)
                        ->orWhere('nidn', 'like', $keyword)
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $keyword)->orWhere('email', 'like', $keyword));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.lecturers.index', ['lecturers' => $lecturers]);
    }

    public function create()
    {
        return view('admin.lecturers.form', ['item' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => ($data['password'] ?? null) ?: $data['nip'], // default: NIP
                'role' => User::ROLE_LECTURER,
            ]);

            $user->lecturer()->create([
                'nip' => $data['nip'],
                'nidn' => ($data['nidn'] ?? null) ?: null,
                'phone' => ($data['phone'] ?? null) ?: null,
            ]);
        });

        return redirect()->route('admin.lecturers.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function edit(Lecturer $lecturer)
    {
        return view('admin.lecturers.form', ['item' => $lecturer->load('user')]);
    }

    public function update(Request $request, Lecturer $lecturer): RedirectResponse
    {
        $data = $this->validated($request, $lecturer);

        DB::transaction(function () use ($data, $lecturer) {
            $userData = ['name' => $data['name'], 'email' => $data['email']];
            if (! empty($data['password'])) {
                $userData['password'] = $data['password'];
            }
            $lecturer->user->update($userData);

            $lecturer->update([
                'nip' => $data['nip'],
                'nidn' => ($data['nidn'] ?? null) ?: null,
                'phone' => ($data['phone'] ?? null) ?: null,
            ]);
        });

        return redirect()->route('admin.lecturers.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Lecturer $lecturer): RedirectResponse
    {
        if ($lecturer->classes()->exists()) {
            return back()->with('error', 'Dosen masih mengampu kelas. Ganti dosen pengampu kelasnya dulu.');
        }

        $lecturer->user->delete();

        return redirect()->route('admin.lecturers.index')->with('success', 'Dosen berhasil dihapus.');
    }

    private function validated(Request $request, ?Lecturer $lecturer = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($lecturer?->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
            'nip' => ['required', 'string', 'max:30', Rule::unique('lecturers', 'nip')->ignore($lecturer)],
            'nidn' => ['nullable', 'string', 'max:30', Rule::unique('lecturers', 'nidn')->ignore($lecturer)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
    }
}
