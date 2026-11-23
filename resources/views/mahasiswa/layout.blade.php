
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Mahasiswa') - SIAKAD</title>

    <link rel="stylesheet"
          href="{{ asset('css/siakad-mahasiswa.css') }}">
</head>
<body class="mhs-app">

<div class="mhs-shell">

    <aside class="mhs-sidebar">

        <a href="{{ route('mahasiswa.dashboard') }}"
           class="mhs-brand">
            <span class="mhs-brand-symbol">S</span>
            <span>
                SIAKAD
                <small>PORTAL MAHASISWA</small>
            </span>
        </a>

        <p class="mhs-nav-caption">MENU UTAMA</p>

        <nav class="mhs-nav">

            <a href="{{ route('mahasiswa.dashboard') }}"
               class="{{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                <span>⌂</span> Dashboard
            </a>

            <a href="{{ route('mahasiswa.katalog') }}"
               class="{{ request()->routeIs('mahasiswa.katalog') ? 'active' : '' }}">
                <span>▦</span> Mata Kuliah
            </a>

            <a href="{{ route('mahasiswa.krs') }}"
               class="{{ request()->routeIs('mahasiswa.krs') ? 'active' : '' }}">
                <span>▤</span> KRS Saya
            </a>

            <a href="{{ route('mahasiswa.jadwal') }}"
               class="{{ request()->routeIs('mahasiswa.jadwal') ? 'active' : '' }}">
                <span>◷</span> Jadwal Kuliah
            </a>

            <a href="{{ route('mahasiswa.nilai') }}"
               class="{{ request()->routeIs('mahasiswa.nilai') ? 'active' : '' }}">
                <span>▥</span> Nilai
            </a>

            <a href="{{ route('mahasiswa.presensi') }}"
               class="{{ request()->routeIs('mahasiswa.presensi') ? 'active' : '' }}">
                <span>✓</span> Presensi
            </a>

        </nav>

        <div class="mhs-sidebar-note">
            Sistem Informasi Akademik
            <br>
            Portal Khusus Mahasiswa
        </div>

    </aside>

    <main class="mhs-main">

        <header class="mhs-topbar">
            <div class="mhs-top-title">
                Sistem Informasi Akademik
            </div>

            <div class="mhs-account">
                <span class="mhs-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'M', 0, 1)) }}
                </span>

                {{ auth()->user()->name ?? 'Mahasiswa' }}
            </div>
        </header>

        <div class="mhs-page">

            @if(session('success'))
                <div class="mhs-flash mhs-flash-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mhs-flash mhs-flash-danger">
                    <strong>Pengajuan belum dapat diproses.</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>

    </main>

</div>

</body>
</html>
