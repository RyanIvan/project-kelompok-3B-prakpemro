<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIAKAD Universitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 min-h-screen flex items-center justify-center p-4">
    <div class="{{ $width ?? 'max-w-md' }} w-full bg-white rounded-2xl shadow-2xl overflow-hidden p-6 sm:p-8 my-6">
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="w-16 h-16 bg-yellow-400 text-blue-950 rounded-full flex items-center justify-center text-3xl mx-auto mb-3 shadow-md">
                <i class="fa-solid fa-graduation-cap"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">@yield('heading')</h1>
            <p class="text-sm text-gray-500 mt-1">@yield('subheading')</p>
        </div>

        @yield('content')

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 font-medium">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Utama
            </a>
        </div>
    </div>
</body>
</html>
