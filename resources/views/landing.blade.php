<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD - Sistem Informasi Akademik Universitas</title>
    <!-- Tailwind CSS (via CDN untuk Quick Prototype) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- 1. NAVBAR PUBLIK -->
    <nav class="bg-blue-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Identity -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center text-blue-900 font-bold text-xl shadow">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-wide block leading-tight">SIAKAD</span>
                        <span class="text-xs text-blue-200 font-light">Sistem Informasi Akademik</span>
                    </div>
                </div>

                <!-- Menu Navigasi -->
                <div class="hidden md:flex items-center space-x-8 font-medium text-sm">
                    <a href="#layanan" class="hover:text-yellow-400 transition">Layanan</a>
                    <a href="#berita" class="hover:text-yellow-400 transition">Berita & Pengumuman</a>
                    <a href="#panduan" class="hover:text-yellow-400 transition">Panduan KRS</a>
                    <a href="#kontak" class="hover:text-yellow-400 transition">Kontak</a>
                </div>

                <!-- Tombol Masuk / Login -->
                <div>
                    <a href="{{ route('login') }}" class="bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-semibold px-5 py-2.5 rounded-lg shadow transition duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Masuk Portal</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <header class="bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 text-white py-20 px-4 relative overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="bg-blue-700 text-yellow-300 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider mb-4 inline-block border border-blue-500">
                Tahun Akademik 2025/2026 Genap
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
                Pusat Layanan Akademik Terpadu
            </h1>
            <p class="text-lg text-blue-100 max-w-2xl mx-auto mb-8 font-light">
                Kemudahan akses pengisian KRS, pemantauan jadwal kuliah, nilai, dan rekapitulasi hasil studi bagi Mahasiswa dan Dosen dalam satu portal.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold px-8 py-3.5 rounded-lg shadow-lg transition">
                    Masuk ke SIAKAD
                </a>
                <a href="#berita" class="bg-blue-800 hover:bg-blue-700 border border-blue-600 text-white font-medium px-6 py-3.5 rounded-lg transition">
                    Lihat Pengumuman
                </a>
            </div>
        </div>
    </header>

    <!-- 3. SECTION LAYANAN UTAMA -->
    <section id="layanan" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Layanan Layanan Utama</h2>
            <p class="text-gray-600 mt-2">Akses cepat portal sesuai peran akademik Anda</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Card Portal Mahasiswa -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition border-t-4 border-t-blue-600">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Portal Mahasiswa</h3>
                <p class="text-gray-600 text-sm mb-4">
                    Pengisian KRS online, melihat jadwal perkuliahan harian, KHS semester, serta pemantauan IPS & IPK.
                </p>
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold text-sm hover:underline inline-flex items-center gap-1">
                    Akses Mahasiswa <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Card Portal Dosen -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition border-t-4 border-t-emerald-600">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Portal Dosen</h3>
                <p class="text-gray-600 text-sm mb-4">
                    Validasi persetujuan KRS mahasiswa bimbingan, pencatatan presensi kelas, dan penginputan nilai Tugas/UTS/UAS.
                </p>
                <a href="{{ route('login') }}" class="text-emerald-600 font-semibold text-sm hover:underline inline-flex items-center gap-1">
                    Akses Dosen <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Card Portal Administrator -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition border-t-4 border-t-purple-600">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl mb-4">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Portal Administrator</h3>
                <p class="text-gray-600 text-sm mb-4">
                    Pengelolaan Data Master (Mahasiswa, Dosen, Matkul), penjadwalan ruang kelas, serta monitoring sistem.
                </p>
                <a href="{{ route('login') }}" class="text-purple-600 font-semibold text-sm hover:underline inline-flex items-center gap-1">
                    Akses Admin <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- 4. SECTION BERITA & PENGUMUMAN AKADEMIK -->
    <section id="berita" class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Pengumuman Terkini</h2>
                    <p class="text-gray-600 mt-1">Informasi penting seputar agenda perkuliahan dan KRS</p>
                </div>
                <a href="#" class="text-blue-900 font-semibold text-sm hover:underline hidden md:block">Lihat Semua Berita</a>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Berita 1 -->
                <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="p-6">
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded">Jadwal Akademik</span>
                        <h3 class="font-bold text-gray-900 text-lg mt-3 mb-2 hover:text-blue-900 transition">
                            Jadwal Pengisian & Pemilihan Mata Kuliah (KRS) Semester Genap 2025/2026
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-2">
                            Pengisian KRS dibuka mulai tanggal 10 - 20 Februari 2026. Mahasiswa diimbau untuk berkonsultasi dengan Dosen PA.
                        </p>
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                            <span><i class="fa-regular fa-calendar mr-1"></i> 02 Feb 2026</span>
                            <span>Oleh: Subbag Akademik</span>
                        </div>
                    </div>
                </article>

                <!-- Berita 2 -->
                <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="p-6">
                        <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded">Aturan Baru</span>
                        <h3 class="font-bold text-gray-900 text-lg mt-3 mb-2 hover:text-blue-900 transition">
                            Prosedur Validasi KRS oleh Dosen Pembimbing Akademik
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-2">
                            Mata kuliah yang telah ditambahkan oleh mahasiswa tidak dapat dibatalkan secara mandiri dan membutuhkan validasi Dosen PA.
                        </p>
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                            <span><i class="fa-regular fa-calendar mr-1"></i> 28 Jan 2026</span>
                            <span>Oleh: BAAK</span>
                        </div>
                    </div>
                </article>

                <!-- Berita 3 -->
                <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="p-6">
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded">Layanan KHS</span>
                        <h3 class="font-bold text-gray-900 text-lg mt-3 mb-2 hover:text-blue-900 transition">
                            Penerbitan KHS dan Rekapitulasi Indeks Prestasi Kumulatif (IPK)
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-2">
                            Hasil nilai semester lalu sudah dipublikasikan. Mahasiswa dapat mengunduh KHS langsung melalui dashboard.
                        </p>
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                            <span><i class="fa-regular fa-calendar mr-1"></i> 15 Jan 2026</span>
                            <span>Oleh: Tim IT SIAKAD</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- 5. FOOTER -->
    <footer class="bg-blue-950 text-white mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <p class="font-bold text-lg">SIAKAD Universitas</p>
                <p class="text-xs text-blue-300">Sistem Informasi Akademik Terpadu &copy; 2026</p>
            </div>
            <div class="text-xs text-blue-300">
                Dikembangkan dengan Laravel & Herd
            </div>
        </div>
    </footer>

</body>
</html>