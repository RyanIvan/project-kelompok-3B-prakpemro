<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register', ['programs' => StudyProgram::orderBy('name')->get()]);
    }

    /**
     * Pendaftaran akun mandiri untuk mahasiswa dan dosen.
     * Kata sandi disimpan sebagai hash bcrypt (cast 'hashed' pada model User).
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_STUDENT, User::ROLE_LECTURER])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Khusus mahasiswa
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:30', 'unique:students,nim'],
            'batch' => ['required_if:role,mahasiswa', 'nullable', 'integer', 'min:2000', 'max:2100'],
            'study_program_id' => ['required_if:role,mahasiswa', 'nullable', 'exists:study_programs,id'],
            // Khusus dosen
            'nip' => ['required_if:role,dosen', 'nullable', 'string', 'max:30', 'unique:lecturers,nip'],
            'nidn' => ['nullable', 'string', 'max:30', 'unique:lecturers,nidn'],
            'phone' => ['nullable', 'string', 'max:30'],
        ], [
            'required_if' => ':attribute wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'nidn.unique' => 'NIDN ini sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ], [
            'name' => 'Nama lengkap',
            'nim' => 'NIM',
            'nip' => 'NIP',
            'nidn' => 'NIDN',
            'batch' => 'Angkatan',
            'study_program_id' => 'Program studi',
            'password' => 'Kata sandi',
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
            ]);
            $user->forceFill(['registered_via' => 'mandiri'])->save();

            if ($data['role'] === User::ROLE_STUDENT) {
                $program = StudyProgram::findOrFail($data['study_program_id']);

                $user->student()->create([
                    'nim' => $data['nim'],
                    'batch' => $data['batch'],
                    'study_program_id' => $program->id,
                    'study_program' => $program->full_name,
                    'status' => 'Aktif',
                ]);
            } else {
                $user->lecturer()->create([
                    'nip' => $data['nip'],
                    'nidn' => ($data['nidn'] ?? null) ?: null,
                    'phone' => ($data['phone'] ?? null) ?: null,
                ]);
            }
        });

        $identity = $data['role'] === User::ROLE_STUDENT ? $data['nim'] : $data['nip'];

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat. Silakan masuk memakai '.($data['role'] === User::ROLE_STUDENT ? 'NIM' : 'NIP').' atau email Anda.')
            ->with('prefill', $identity);
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['nullable', 'in:mahasiswa,dosen,admin'],
            'identity' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'identity.required' => 'Email / NIM / NIP wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $throttleKey = Str::lower($data['identity']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors(['identity' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik."])
                ->onlyInput('identity');
        }

        $user = $this->findUser($data['role'] ?? null, $data['identity']);

        if ($user === null || ! Hash::check($data['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()
                ->withErrors(['identity' => 'Akun tidak ditemukan atau kata sandi salah.'])
                ->onlyInput('identity');
        }

        if (! $user->is_active) {
            return back()
                ->withErrors(['identity' => 'Akun Anda dinonaktifkan. Hubungi administrator.'])
                ->onlyInput('identity');
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $user->forceFill(['last_login_at' => now(), 'last_login_ip' => $request->ip()])->saveQuietly();

        return redirect()->to($this->homeFor($user));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Cari user berdasarkan email, NIM (mahasiswa), NIP (dosen) atau ID staf (admin).
     * Peran boleh dikosongkan: sistem menentukannya dari akun yang ditemukan.
     */
    private function findUser(?string $role, string $identity): ?User
    {
        $identity = trim($identity);

        return User::query()
            ->when($role, fn ($q) => $q->where('role', $role))
            ->where(function ($query) use ($identity) {
                $query->where('email', $identity)
                    ->orWhereHas('student', fn ($q) => $q->where('nim', $identity))
                    ->orWhereHas('lecturer', fn ($q) => $q->where('nip', $identity))
                    ->orWhereHas('admin', fn ($q) => $q->where('staff_id', $identity));
            })
            ->first();
    }

    private function homeFor(User $user): string
    {
        $routeName = match ($user->role) {
            User::ROLE_ADMIN => 'admin.dashboard',
            User::ROLE_LECTURER => 'dosen.dashboard',
            default => 'mahasiswa.dashboard',
        };

        return Route::has($routeName) ? route($routeName) : route('home');
    }
}
