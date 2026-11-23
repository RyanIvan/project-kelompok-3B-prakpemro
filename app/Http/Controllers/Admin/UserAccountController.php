<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Pemantauan akun untuk admin: siapa yang sudah mendaftar, kapan terakhir login,
 * kapan kata sandi dibuat/diubah, serta aktif/nonaktifkan dan reset kata sandi.
 *
 * Kata sandi disimpan sebagai hash satu arah (bcrypt), jadi tidak bisa dibaca siapa pun,
 * termasuk admin. Jika pengguna lupa, admin membuat kata sandi sementara lewat reset.
 */
class UserAccountController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['student', 'lecturer', 'admin'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword)
                        ->orWhereHas('student', fn ($s) => $s->where('nim', 'like', $keyword))
                        ->orWhereHas('lecturer', fn ($l) => $l->where('nip', 'like', $keyword))
                        ->orWhereHas('admin', fn ($a) => $a->where('staff_id', 'like', $keyword));
                });
            })
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->input('role')))
            ->when($request->input('status') === 'aktif', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'nonaktif', fn ($q) => $q->where('is_active', false))
            ->when($request->input('via') === 'mandiri', fn ($q) => $q->where('registered_via', 'mandiri'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.accounts.index', [
            'users' => $users,
            'totals' => [
                'all' => User::count(),
                'mahasiswa' => User::where('role', User::ROLE_STUDENT)->count(),
                'dosen' => User::where('role', User::ROLE_LECTURER)->count(),
                'mandiri' => User::where('registered_via', 'mandiri')->count(),
                'nonaktif' => User::where('is_active', false)->count(),
            ],
        ]);
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        $user->forceFill(['is_active' => ! $user->is_active])->save();

        if (! $user->is_active) {
            $this->endSessions($user);
        }

        return back()->with('success', 'Akun '.$user->name.($user->is_active ? ' diaktifkan kembali.' : ' dinonaktifkan.'));
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $temporary = Str::password(10, symbols: false);

        $user->update(['password' => $temporary]);
        $this->endSessions($user);

        // Ditampilkan sekali saja; tidak disimpan dalam bentuk apa pun selain hash.
        return back()->with('reset_info', ['name' => $user->name, 'password' => $temporary]);
    }

    /** Keluarkan perangkat yang sedang login (hanya berlaku jika SESSION_DRIVER=database). */
    private function endSessions(User $user): void
    {
        if (config('session.driver') === 'database' && Schema::hasTable(config('session.table', 'sessions'))) {
            DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
        }
    }
}
