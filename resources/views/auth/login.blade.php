<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAKAD Universitas</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden p-8">
        
        <!-- Header Logo / Title -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-yellow-400 text-blue-950 rounded-full flex items-center justify-center text-3xl mx-auto mb-3 shadow-md">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Portal SIAKAD</h1>
            <p class="text-sm text-gray-500 mt-1">Silakan masuk menggunakan akun Anda</p>
        </div>

        <!-- Form Login -->
        <form action="#" method="POST">
            @csrf

            <!-- Pilihan Role (Opsional untuk simulasi multi-role) -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Login Sebagai</label>
                <select name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:outline-none text-sm bg-gray-50">
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <!-- Email / NIM / NIP -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Email / NIM / NIP</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" name="identity" required placeholder="Masukkan NIM atau Email" 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:outline-none text-sm">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" required placeholder="••••••••" 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:outline-none text-sm">
                </div>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-lg shadow-md transition duration-200 flex items-center justify-center gap-2">
                <span>Masuk ke Dashboard</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <!-- Kembali ke Beranda -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 font-medium">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Utama
            </a>
        </div>

    </div>

</body>
</html>