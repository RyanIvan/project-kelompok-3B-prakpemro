@extends('layouts.guest')

@section('title', 'Masuk')
@section('heading', 'Portal SIAKAD')
@section('subheading', 'Silakan masuk menggunakan akun Anda')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-4">
            <label for="identity" class="block text-xs font-semibold text-gray-600 uppercase mb-2">Email / NIM / NIP</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-user"></i></span>
                <input id="identity" type="text" name="identity" value="{{ old('identity', session('prefill')) }}" required autofocus
                       placeholder="Masukkan email, NIM, atau NIP"
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:outline-none text-sm">
            </div>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-xs font-semibold text-gray-600 uppercase mb-2">Kata Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fa-solid fa-lock"></i></span>
                <input id="password" type="password" name="password" required placeholder="••••••••"
                       class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:outline-none text-sm">
                <button type="button" onclick="togglePw('password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="Tampilkan kata sandi">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-600 mb-6">
            <input type="checkbox" name="remember" value="1" class="rounded border-gray-300"> Ingat saya
        </label>

        <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-lg shadow-md transition duration-200 flex items-center justify-center gap-2">
            <span>Masuk ke Dashboard</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-5">
        Belum punya akun? <a href="{{ route('register') }}" class="text-blue-700 font-semibold hover:underline">Daftar sekarang</a>
    </p>
    <p class="text-center text-xs text-gray-400 mt-2">Lupa kata sandi? Hubungi administrator (BAAK) untuk direset.</p>

    <script>
        function togglePw(id, btn) {
            const input = document.getElementById(id);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('i').className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
        }
    </script>
@endsection
