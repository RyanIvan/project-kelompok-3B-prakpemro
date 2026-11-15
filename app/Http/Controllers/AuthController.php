<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:mahasiswa,dosen,admin'],
            'identity' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->findUser($data['role'], $data['identity']);

        if ($user === null || ! Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['identity' => 'Akun tidak ditemukan atau kata sandi salah.'])
                ->onlyInput('identity', 'role');
        }

        Auth::login($user);
        $request->session()->regenerate();

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
     * Cari user berdasarkan role dan identitas: email, atau NIM (mahasiswa),
     * NIP (dosen), ID staf (admin).
     */
    private function findUser(string $role, string $identity): ?User
    {
        return User::query()
            ->where('role', $role)
            ->where(function ($query) use ($role, $identity) {
                $query->where('email', $identity);

                match ($role) {
                    User::ROLE_STUDENT => $query->orWhereHas('student', fn ($q) => $q->where('nim', $identity)),
                    User::ROLE_LECTURER => $query->orWhereHas('lecturer', fn ($q) => $q->where('nip', $identity)),
                    User::ROLE_ADMIN => $query->orWhereHas('admin', fn ($q) => $q->where('staff_id', $identity)),
                    default => null,
                };
            })
            ->first();
    }

    /**
     * Tujuan setelah login. Dashboard dosen & mahasiswa dibuat oleh anggota tim lain:
     * begitu route 'dosen.dashboard' / 'mahasiswa.dashboard' ada, otomatis dipakai.
     */
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
