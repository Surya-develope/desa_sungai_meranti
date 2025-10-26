<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Sungai Meranti - Kec. Pinggir, Bengkalis</title>
    <!-- Memuat Tailwind CSS dan Font Inter -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans text-gray-800 bg-gray-50 antialiased">

    <!-- Navbar telah dihapus, halaman akan dimulai dari main content -->

    <!-- Placeholder untuk Konten Halaman (tempat index.blade.php akan disuntikkan) -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Script untuk Tracking (Harus ada di layout karena digunakan di index.blade.php) -->
    <script>
        function trackSurat(event) {
            event.preventDefault();
            const kode = document.getElementById('tracking_code').value.trim();
            if (kode) {
                window.location.href = `/tracking/${kode}`;
            } else {
                console.error('Mohon masukkan Kode Tracking Anda.');
            }
        }
    </script>
</body>
</html>
