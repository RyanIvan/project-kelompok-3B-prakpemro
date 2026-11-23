@extends('layouts.admin')
@section('title', 'Akun & Kata Sandi')

@section('content')
    <x-admin.page-header title="Akun & Kata Sandi Pengguna" subtitle="Pantau akun yang sudah mendaftar, aktivitas masuk, dan reset kata sandi bila pengguna lupa." />

    @if (session('reset_info'))
        <div class="mb-5 rounded-xl border-2 border-yellow-400 bg-yellow-50 p-5">
            <p class="font-bold text-gray-900"><i class="fa-solid fa-key text-yellow-600 mr-1"></i> Kata sandi sementara untuk {{ session('reset_info')['name'] }}</p>
            <div class="mt-2 flex flex-wrap items-center gap-3">
                <code id="temp-password" class="bg-white border border-yellow-300 rounded-lg px-4 py-2 text-lg font-mono tracking-wider select-all">{{ session('reset_info')['password'] }}</code>
                <button type="button" class="text-sm bg-blue-900 hover:bg-blue-800 text-white px-3 py-2 rounded-lg"
                        onclick="navigator.clipboard.writeText(document.getElementById('temp-password').textContent); this.textContent='Tersalin';">
                    <i class="fa-regular fa-copy mr-1"></i> Salin
                </button>
            </div>
            <p class="text-xs text-gray-600 mt-2">Catat dan sampaikan ke pengguna sekarang. Kata sandi ini hanya tampil sekali, dan pengguna disarankan menggantinya setelah masuk.</p>
        </div>
    @endif

    <!-- Ringkasan -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-5">
        @foreach ([
            ['Total Akun', $totals['all'], 'text-gray-900', null],
            ['Mahasiswa', $totals['mahasiswa'], 'text-blue-700', ['role' => 'mahasiswa']],
            ['Dosen', $totals['dosen'], 'text-emerald-700', ['role' => 'dosen']],
            ['Daftar Mandiri', $totals['mandiri'], 'text-purple-700', ['via' => 'mandiri']],
            ['Nonaktif', $totals['nonaktif'], 'text-red-600', ['status' => 'nonaktif']],
        ] as [$label, $value, $color, $query])
            <a href="{{ route('admin.accounts.index', $query ?? []) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <p class="text-xs text-gray-500">{{ $label }}</p>
                <p class="text-2xl font-bold {{ $color }}">{{ number_format($value, 0, ',', '.') }}</p>
            </a>
        @endforeach
    </div>

    <x-admin.filter-bar :action="route('admin.accounts.index')">
        <div class="w-64">
            <x-admin.input name="q" label="Cari" :value="request('q')" placeholder="Nama / email / NIM / NIP" />
        </div>
        <div class="w-40">
            <x-admin.select name="role" label="Peran" :options="['mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen', 'admin' => 'Admin']" :value="request('role')" placeholder="Semua" />
        </div>
        <div class="w-40">
            <x-admin.select name="status" label="Status" :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']" :value="request('status')" placeholder="Semua" />
        </div>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Pengguna</th><th>Peran</th><th>NIM / NIP / ID</th><th>Terdaftar</th>
                    <th>Terakhir Masuk</th><th>Kata Sandi</th><th>Status</th><th class="px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t align-top">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </td>
                        <td>
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ ['admin' => 'bg-purple-100 text-purple-700', 'dosen' => 'bg-emerald-100 text-emerald-700'][$user->role] ?? 'bg-blue-100 text-blue-700' }}">
                                {{ $user->roleLabel() }}
                            </span>
                        </td>
                        <td class="font-mono text-xs">{{ $user->identityNumber() ?? '-' }}</td>
                        <td class="text-xs text-gray-600">
                            {{ $user->created_at?->format('d M Y H:i') }}
                            <span class="block text-gray-400">{{ $user->registered_via === 'mandiri' ? 'Daftar mandiri' : 'Dibuat admin' }}</span>
                        </td>
                        <td class="text-xs text-gray-600">
                            @if ($user->last_login_at)
                                {{ $user->last_login_at->format('d M Y H:i') }}
                                <span class="block text-gray-400">{{ $user->last_login_ip }}</span>
                            @else
                                <span class="text-gray-400">Belum pernah</span>
                            @endif
                        </td>
                        <td class="text-xs text-gray-600">
                            <span class="font-mono tracking-widest text-gray-500">••••••••</span>
                            <span class="block text-gray-400">
                                Tersimpan terenkripsi{{ $user->password_changed_at ? ', diubah '.$user->password_changed_at->format('d M Y') : '' }}
                            </span>
                        </td>
                        <td>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4">
                            <div class="flex items-center justify-end gap-1 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.accounts.reset-password', $user) }}"
                                      onsubmit="return confirm('Reset kata sandi {{ e($user->name) }}? Kata sandi lama tidak berlaku lagi dan perangkatnya akan keluar.')">
                                    @csrf
                                    <button type="submit" class="text-blue-700 hover:bg-blue-50 px-2.5 py-1.5 rounded-lg text-xs font-semibold">
                                        <i class="fa-solid fa-key"></i> Reset
                                    </button>
                                </form>
                                @unless ($user->is(auth()->user()))
                                    <form method="POST" action="{{ route('admin.accounts.toggle-active', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $user->is_active ? 'text-red-600 hover:bg-red-50' : 'text-emerald-700 hover:bg-emerald-50' }} px-2.5 py-1.5 rounded-lg text-xs font-semibold">
                                            <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada akun yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>

    <p class="text-xs text-gray-500 mt-4">
        <i class="fa-solid fa-lock mr-1"></i>
        Kata sandi disimpan sebagai hash satu arah (bcrypt) sehingga tidak dapat dibaca siapa pun, termasuk admin. Itu standar keamanan; bila pengguna lupa, gunakan tombol Reset.
    </p>
@endsection
