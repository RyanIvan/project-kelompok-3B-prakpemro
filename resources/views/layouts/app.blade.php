<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIAKAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

@php
    $authUser = auth()->user();

    $menus = [
        'admin' => [
            ['label' => 'Dashboard', 'icon' => 'fa-house', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
            ['section' => 'Struktur Akademik'],
            ['label' => 'Fakultas', 'icon' => 'fa-building-columns', 'route' => 'admin.faculties.index', 'match' => 'admin.faculties.*'],
            ['label' => 'Jurusan', 'icon' => 'fa-sitemap', 'route' => 'admin.departments.index', 'match' => 'admin.departments.*'],
            ['label' => 'Program Studi', 'icon' => 'fa-layer-group', 'route' => 'admin.study-programs.index', 'match' => 'admin.study-programs.*'],
            ['section' => 'Data Master'],
            ['label' => 'Mahasiswa', 'icon' => 'fa-user-graduate', 'route' => 'admin.students.index', 'match' => 'admin.students.*'],
            ['label' => 'Dosen', 'icon' => 'fa-chalkboard-user', 'route' => 'admin.lecturers.index', 'match' => 'admin.lecturers.*'],
            ['label' => 'Mata Kuliah', 'icon' => 'fa-book', 'route' => 'admin.courses.index', 'match' => 'admin.courses.*'],
            ['label' => 'Ruang', 'icon' => 'fa-door-open', 'route' => 'admin.rooms.index', 'match' => 'admin.rooms.*'],
            ['section' => 'Perkuliahan'],
            ['label' => 'Kelas', 'icon' => 'fa-chalkboard', 'route' => 'admin.classes.index', 'match' => 'admin.classes.*'],
            ['label' => 'Jadwal', 'icon' => 'fa-calendar-days', 'route' => 'admin.schedules.index', 'match' => 'admin.schedules.*'],
            ['label' => 'Monitoring KRS', 'icon' => 'fa-clipboard-check', 'route' => 'admin.enrollments.index', 'match' => 'admin.enrollments.*'],
            ['section' => 'Pengguna'],
            ['label' => 'Akun & Kata Sandi', 'icon' => 'fa-key', 'route' => 'admin.accounts.index', 'match' => 'admin.accounts.*'],
        ],
        'dosen' => [
            ['label' => 'Dashboard', 'icon' => 'fa-house', 'route' => 'dosen.dashboard', 'match' => 'dosen.dashboard'],
            ['section' => 'Perkuliahan'],
            ['label' => 'Jadwal Mengajar', 'icon' => 'fa-calendar-days', 'route' => 'dosen.schedules.index', 'match' => 'dosen.schedules.*'],
            ['label' => 'Kelas Saya', 'icon' => 'fa-chalkboard-user', 'route' => 'dosen.classes.index', 'match' => 'dosen.classes.*'],
            ['section' => 'Penilaian'],
            ['label' => 'Nilai Mahasiswa', 'icon' => 'fa-clipboard-check', 'route' => 'dosen.grades.index', 'match' => 'dosen.grades.*'],
        ],
        'mahasiswa' => [
            ['label' => 'Dashboard', 'icon' => 'fa-house', 'route' => 'mahasiswa.dashboard', 'match' => 'mahasiswa.dashboard'],
            ['section' => 'Perkuliahan'],
            ['label' => 'Mata Kuliah', 'icon' => 'fa-book', 'route' => 'mahasiswa.katalog', 'match' => 'mahasiswa.katalog'],
            ['label' => 'KRS Saya', 'icon' => 'fa-clipboard-list', 'route' => 'mahasiswa.krs', 'match' => 'mahasiswa.krs'],
            ['label' => 'Jadwal Kuliah', 'icon' => 'fa-calendar-days', 'route' => 'mahasiswa.jadwal', 'match' => 'mahasiswa.jadwal'],
            ['section' => 'Hasil Studi'],
            ['label' => 'Nilai', 'icon' => 'fa-chart-simple', 'route' => 'mahasiswa.nilai', 'match' => 'mahasiswa.nilai'],
            ['label' => 'Presensi', 'icon' => 'fa-user-check', 'route' => 'mahasiswa.presensi', 'match' => 'mahasiswa.presensi'],
        ],
    ];
    $menu = $menus[$authUser->role] ?? $menus['mahasiswa'];
    $portalLabel = ['admin' => 'Panel Administrator', 'dosen' => 'Portal Dosen', 'mahasiswa' => 'Portal Mahasiswa'][$authUser->role] ?? 'Portal';
    $idLabel = ['admin' => 'ID Staf', 'dosen' => 'NIP', 'mahasiswa' => 'NIM'][$authUser->role] ?? 'ID';
    $homeRoute = ['admin' => 'admin.dashboard', 'dosen' => 'dosen.dashboard', 'mahasiswa' => 'mahasiswa.dashboard'][$authUser->role] ?? 'home';
@endphp

<div class="flex min-h-screen">
    <!-- Overlay menu (mobile) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-blue-950 text-white flex-shrink-0 flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-200">
        <a href="{{ route($homeRoute) }}" class="h-20 flex items-center gap-3 px-5 border-b border-blue-900">
            <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-blue-900 text-lg">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <p class="font-bold leading-tight">SIAKAD</p>
                <p class="text-[11px] text-blue-300">{{ $portalLabel }}</p>
            </div>
        </a>

        <nav class="flex-1 overflow-y-auto py-4 px-3 text-sm">
            @foreach ($menu as $navItem)
                @isset($navItem['section'])
                    <p class="text-[11px] uppercase tracking-wider text-blue-400 font-semibold px-3 mt-5 mb-1">{{ $navItem['section'] }}</p>
                @else
                    <a href="{{ route($navItem['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 transition
                              {{ request()->routeIs($navItem['match']) ? 'bg-blue-600 text-white font-semibold' : 'text-blue-100 hover:bg-blue-900' }}">
                        <i class="fa-solid {{ $navItem['icon'] }} w-5 text-center"></i>
                        <span>{{ $navItem['label'] }}</span>
                    </a>
                @endisset
            @endforeach
        </nav>

        <div class="p-4 border-t border-blue-900 text-xs text-blue-300">
            <p class="font-semibold text-white truncate">{{ $authUser->name }}</p>
            <p class="truncate text-[11px]">{{ $idLabel }}: {{ $authUser->identityNumber() ?? '-' }}</p>
        </div>
    </aside>

    <!-- KONTEN -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" class="md:hidden text-gray-600 hover:text-gray-900" onclick="toggleSidebar()" aria-label="Buka menu">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-lg font-bold text-gray-900 truncate">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="text-right leading-tight hidden sm:block">
                    <p class="text-sm font-semibold">{{ $authUser->name }}</p>
                    <p class="text-xs text-gray-500">{{ $authUser->roleLabel() }}</p>
                </div>
                <a href="{{ route('account.password') }}" title="Ganti kata sandi"
                   class="text-sm bg-gray-100 hover:bg-blue-50 hover:text-blue-700 px-3 py-2 rounded-lg transition">
                    <i class="fa-solid fa-key"></i><span class="hidden lg:inline ml-1">Kata Sandi</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-gray-100 hover:bg-red-50 hover:text-red-600 px-3 py-2 rounded-lg transition">
                        <i class="fa-solid fa-right-from-bracket"></i><span class="hidden sm:inline ml-1">Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="p-4 sm:p-6 flex-1">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                    <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
                </div>
            @endif
            @if ($errors->has('class_id'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first('class_id') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="px-6 py-4 text-xs text-gray-400 text-center border-t border-gray-200 bg-white">
            SIAKAD Universitas &copy; {{ now()->year }}
        </footer>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.toggle('hidden');
    }
</script>
</body>
</html>
