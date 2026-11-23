@extends('layouts.app')
@section('title', 'Ganti Kata Sandi')

@section('content')
    <x-admin.page-header title="Ganti Kata Sandi" subtitle="Gunakan kata sandi yang kuat dan jangan dibagikan ke siapa pun." />

    <form method="POST" action="{{ route('account.password.update') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <x-admin.input name="current_password" type="password" label="Kata Sandi Saat Ini" required />
            <x-admin.input name="password" type="password" label="Kata Sandi Baru" hint="Minimal 8 karakter." required />
            <x-admin.input name="password_confirmation" type="password" label="Ulangi Kata Sandi Baru" required />
        </div>
        <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow transition">
                <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan
            </button>
        </div>
    </form>
@endsection
