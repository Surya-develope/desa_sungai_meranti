<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Sungai Meranti</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans">
    <nav class="bg-green-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('logo-desa.png') }}" alt="Logo Desa" class="w-10 h-10">
                <span class="text-lg font-semibold">Desa Sungai Meranti</span>
            </div>
            <div class="space-x-6">
                <a href="{{ route('home') }}" class="hover:text-yellow-300">Beranda</a>
                <a href="{{ route('pengajuan.create') }}" class="hover:text-yellow-300">Pembuatan Surat</a>
                <a href="{{ route('penduduk') }}" class="hover:text-yellow-300">Data Penduduk</a>
                <a href="{{ route('profil') }}" class="hover:text-yellow-300">Profil Desa</a>
            </div>
        </div>
    </nav>

    <main class="min-h-screen py-10">
        @yield('content')
    </main>

    <footer class="bg-green-700 text-white text-center py-3 mt-10">
        <p>© 2025 Desa Sungai Meranti. Semua hak dilindungi.</p>
    </footer>
</body>
</html>
