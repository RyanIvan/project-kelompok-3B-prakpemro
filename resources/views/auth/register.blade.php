@extends('layouts.guest', ['width' => 'max-w-2xl'])

@section('title', 'Daftar Akun')
@section('heading', 'Buat Akun SIAKAD')
@section('subheading', 'Daftar sebagai mahasiswa atau dosen, lalu langsung masuk')

@php $role = old('role', request('role', 'mahasiswa')); @endphp

@section('content')
    <form action="{{ route('register.store') }}" method="POST" id="register-form">
        @csrf

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                <p class="font-semibold mb-1">Pendaftaran belum berhasil:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <!-- Pilih peran -->
        <div class="grid grid-cols-2 gap-3 mb-5">
            @foreach (['mahasiswa' => ['Mahasiswa', 'fa-user-graduate'], 'dosen' => ['Dosen', 'fa-chalkboard-user']] as $value => [$label, $icon])
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="{{ $value }}" class="peer sr-only" @checked($role === $value) onchange="switchRole(this.value)">
                    <div class="rounded-xl border-2 border-gray-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 p-3 text-center transition">
                        <i class="fa-solid {{ $icon }} text-xl text-blue-700"></i>
                        <p class="text-sm font-semibold mt-1">{{ $label }}</p>
                    </div>
                </label>
            @endforeach
        </div>

        @php
            $field = 'w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none';
            $lbl = 'block text-xs font-semibold text-gray-600 uppercase mb-1';
        @endphp

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="{{ $lbl }}" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
                <input id="name" name="name" value="{{ old('name') }}" required class="{{ $field }}">
            </div>
            <div>
                <label class="{{ $lbl }}" for="email">Email <span class="text-red-500">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="{{ $field }}">
            </div>

            <!-- Mahasiswa -->
            <div data-role="mahasiswa">
                <label class="{{ $lbl }}" for="nim">NIM <span class="text-red-500">*</span></label>
                <input id="nim" name="nim" value="{{ old('nim') }}" class="{{ $field }}">
            </div>
            <div data-role="mahasiswa">
                <label class="{{ $lbl }}" for="batch">Angkatan <span class="text-red-500">*</span></label>
                <input id="batch" type="number" name="batch" value="{{ old('batch', now()->year) }}" class="{{ $field }}">
            </div>
            <div data-role="mahasiswa" class="sm:col-span-2">
                <label class="{{ $lbl }}" for="study_program_id">Program Studi <span class="text-red-500">*</span></label>
                <select id="study_program_id" name="study_program_id" class="{{ $field }} bg-white">
                    <option value="">— Pilih program studi —</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}" @selected((string) old('study_program_id') === (string) $program->id)>{{ $program->full_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dosen -->
            <div data-role="dosen">
                <label class="{{ $lbl }}" for="nip">NIP <span class="text-red-500">*</span></label>
                <input id="nip" name="nip" value="{{ old('nip') }}" class="{{ $field }}">
            </div>
            <div data-role="dosen">
                <label class="{{ $lbl }}" for="nidn">NIDN (opsional)</label>
                <input id="nidn" name="nidn" value="{{ old('nidn') }}" class="{{ $field }}">
            </div>
            <div data-role="dosen" class="sm:col-span-2">
                <label class="{{ $lbl }}" for="phone">No. Telepon (opsional)</label>
                <input id="phone" name="phone" value="{{ old('phone') }}" class="{{ $field }}">
            </div>

            <div>
                <label class="{{ $lbl }}" for="password">Kata Sandi <span class="text-red-500">*</span></label>
                <input id="password" type="password" name="password" required minlength="8" class="{{ $field }}">
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter.</p>
            </div>
            <div>
                <label class="{{ $lbl }}" for="password_confirmation">Ulangi Kata Sandi <span class="text-red-500">*</span></label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="{{ $field }}">
            </div>
        </div>

        <button type="submit" class="w-full mt-6 bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-lg shadow-md transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Buat Akun
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-5">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-700 font-semibold hover:underline">Masuk</a>
    </p>

    <script>
        function switchRole(role) {
            document.querySelectorAll('[data-role]').forEach(function (el) {
                const active = el.dataset.role === role;
                el.classList.toggle('hidden', !active);
                el.querySelectorAll('input, select').forEach(function (input) {
                    input.disabled = !active; // field peran lain tidak ikut terkirim
                });
            });
        }
        switchRole(document.querySelector('input[name=role]:checked').value);
    </script>
@endsection
