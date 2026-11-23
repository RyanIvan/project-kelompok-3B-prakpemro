<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIAKAD Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-800">

@php
    $menu = [
        ['label' => 'Dashboard', 'icon' => 'fa-house', 'route' => 'dosen.dashboard', 'match' => 'dosen.dashboard'],
        ['section' => 'Perkuliahan'],
        ['label' => 'Jadwal Mengajar', 'icon' => 'fa-calendar-days', 'route' => 'dosen.schedules.index', 'match' => 'dosen.schedules.*'],
        ['label' => 'Kelas Saya', 'icon' => 'fa-chalkboard-user', 'route' => 'dosen.classes.index', 'match' => 'dosen.classes.*'],
        ['section' => 'Penilaian'],
        ['label' => 'Nilai Mahasiswa', 'icon' => 'fa-clipboard-check', 'route' => 'dosen.grades.index', 'match' => 'dosen.grades.*'],
    ];
@endphp

<div class="flex min-h-screen">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-950 text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="h-20 flex items-center gap-3 px-5 border-b border-blue-900">
            <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-blue-950 text-lg shadow-sm">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <p class="font-bold leading-tight tracking-wide">SIAKAD</p>
                <p class="text-[11px] text-blue-300 font-medium">Portal Dosen</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 text-sm">
            @foreach ($menu as $navItem)
                @isset($navItem['section'])
                    <p class="text-[11px] uppercase tracking-wider text-blue-400 font-semibold px-3 mt-5 mb-1">{{ $navItem['section'] }}</p>
                @else
                    <a href="{{ route($navItem['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 transition
                              {{ request()->routeIs($navItem['match']) ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-blue-100 hover:bg-blue-900 hover:text-white' }}">
                        <i class="fa-solid {{ $navItem['icon'] }} w-5 text-center"></i>
                        <span>{{ $navItem['label'] }}</span>
                    </a>
                @endisset
            @endforeach
        </nav>

        <div class="p-4 border-t border-blue-900/60 bg-blue-950/40 text-xs text-blue-300">
            <p class="font-semibold text-white truncate">{{ auth()->user()->name }}</p>
            <p class="truncate text-[11px]">NIP: {{ auth()->user()->lecturer?->nip ?? '-' }}</p>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="flex-1 flex flex-col min-w-0">
        <header class="h-20 bg-white border-b border-slate-200 shadow-sm flex items-center justify-between px-6">
            <div class="flex items-center gap-3">
                <div class="md:hidden">
                    <button type="button" class="text-slate-600 hover:text-slate-900" onclick="alert('Buka menu di tampilan desktop')">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
                <h1 class="text-lg font-bold text-slate-800">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right leading-tight hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-600 font-medium">Dosen Pengampu</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 px-3 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="p-6 flex-1 max-w-7xl w-full mx-auto">
            @if (session('success'))
                <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 text-sm flex items-start gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-600 text-base"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 text-sm flex items-start gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-rose-600 text-base"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
