<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** Ganti kata sandi sendiri (semua peran). */
class AccountController extends Controller
{
    public function editPassword()
    {
        return view('account.password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini salah.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sama.',
            'password.different' => 'Kata sandi baru harus berbeda dari yang lama.',
        ]);

        $request->user()->update(['password' => $data['password']]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
