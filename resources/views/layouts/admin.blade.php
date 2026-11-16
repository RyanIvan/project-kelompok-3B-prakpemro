<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIAKAD Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

@php
    $menu = [
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
    ];
@endphp

<div class="flex min-h-screen">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-950 text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="h-20 flex items-center gap-3 px-5 border-b border-blue-900">
            <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-blue-900 text-lg">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <p class="font-bold leading-tight">SIAKAD</p>
                <p class="text-[11px] text-blue-300">Panel Administrator</p>
            </div>
        </div>

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
    </aside>

    <!-- KONTEN -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-6">
            <h1 class="text-lg font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <div class="text-right leading-tight">
                    <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-gray-100 hover:bg-red-50 hover:text-red-600 px-3 py-2 rounded-lg transition">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i> Keluar
                    </button>
                </form>
            </div>
        </header>

        <main class="p-6 flex-1">
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

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
