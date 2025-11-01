<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Desa Sungai Meranti</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Enhanced Navbar -->
    <nav class="bg-green-700 text-white shadow-xl sticky top-0 z-50">
        <div class="container mx-auto px-8">
            <div class="flex justify-between items-center py-3">
                <!-- Logo and Brand -->
                <a href="{{ route('home') }}" class="flex items-center space-x-4 hover:bg-green-600 rounded-lg p-2 transition-all duration-300 cursor-pointer">
                    <img src="{{ asset('logo-desa.png') }}" alt="Logo Desa" class="w-12 h-12 rounded-full border-2 border-white/20">
                    <div>
                        <h1 class="text-xl font-bold tracking-wide">Pemerintah Desa Sungai Meranti</h1>
                        <p class="text-green-100 text-sm">Kacamatan Pinggir Kabupaten Bengkalis</p>
                    </div>
                </a>
                
                <!-- Desktop Auth Section -->
                <div class="hidden lg:flex items-center space-x-4">
                    @auth
                        <!-- User Info Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center space-x-3 bg-green-600 hover:bg-green-500 rounded-lg px-4 py-2 transition-all duration-200">
                                <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <span class="text-green-900 font-bold text-sm">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</span>
                                </div>
                                <div class="text-left">
                                    <p class="text-white font-medium text-sm">{{ Auth::user()->nama }}</p>
                                    <p class="text-green-100 text-xs capitalize">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                                </div>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <div class="py-2">
                                    <div class="px-4 py-2 border-b border-gray-200">
                                        <p class="text-gray-900 font-medium text-sm">{{ Auth::user()->nama }}</p>
                                        <p class="text-gray-600 text-xs">{{ Auth::user()->email }}</p>
                                    </div>
                                    @if(Auth::user()->role && Auth::user()->role->nama_role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v14a2 2 0 01-2 2H8a2 2 0 01-2-2V5z"></path>
                                            </svg>
                                            Dashboard Admin
                                        </a>
                                    @else
                                        <a href="{{ route('warga.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Dashboard Saya
                                        </a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 text-sm">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-yellow-300 px-4 py-2 rounded-lg font-medium transition-colors duration-200 border border-white/20 hover:border-yellow-300">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-yellow-400 hover:bg-yellow-300 text-green-900 px-4 py-2 rounded-lg font-bold transition-all duration-200">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden focus:outline-none" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div class="lg:hidden hidden" id="mobile-menu">
                <div class="px-4 pt-2 pb-3 space-y-1 border-t border-green-600">
                    @auth
                        <!-- Mobile User Info -->
                        <div class="px-2 py-3 border-b border-green-600">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <span class="text-green-900 font-bold">{{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ Auth::user()->nama }}</p>
                                    <p class="text-green-200 text-sm capitalize">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @if(Auth::user()->role && Auth::user()->role->nama_role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-2 py-2 text-white hover:bg-green-600 rounded-lg">Dashboard Admin</a>
                            @else
                                <a href="{{ route('warga.dashboard') }}" class="block px-2 py-2 text-white hover:bg-green-600 rounded-lg">Dashboard Saya</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="block w-full text-left px-2 py-2 text-red-200 hover:bg-red-600 rounded-lg">Keluar</button>
                            </form>
                        </div>
                    @else
                        <div class="pt-4 mt-4 border-t border-green-600 space-y-2">
                            <a href="{{ route('login') }}" class="block w-full text-center text-white hover:text-yellow-300 px-4 py-2 rounded-lg font-medium transition-colors duration-200 border border-white/20 hover:border-yellow-300">Masuk</a>
                            <a href="{{ route('register') }}" class="block w-full text-center bg-yellow-400 hover:bg-yellow-300 text-green-900 px-4 py-2 rounded-lg font-bold transition-all duration-200">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu JavaScript -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>

    <!-- Notification Container -->
    @include('components.notification-container')

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Enhanced Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 mb-4 hover:bg-gray-800 rounded-lg p-2 transition-all duration-300 cursor-pointer">
                        <img src="{{ asset('logo-desa.png') }}" alt="Logo Desa" class="w-12 h-12 rounded-full">
                        <div>
                            <h3 class="text-xl font-bold">Desa Sungai Meranti</h3>
                            <p class="text-gray-300 text-sm">Kabupaten Bengkalis, Provinsi Riau</p>
                        </div>
                    </a>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Melayani masyarakat dengan sistem informasi desa yang modern, transparan, dan mudah diakses.
                        Semua layanan administrasi desa dalam satu platform digital yang terdepan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-emerald-400">Menu Utama</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ route('pengajuan.create') }}" class="text-gray-300 hover:text-white transition-colors">Buat Surat</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Data Penduduk</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Profil Desa</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Berita Desa</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-emerald-400">Kontak</h4>
                    <div class="space-y-3 text-gray-300">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm">Sungai Meranti, Kec. Pinggir, Kab. Bengkalis</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-sm">(0761) 123-456</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm">info@desasungaimeranti.id</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p class="text-gray-400">
                    © 2025 Desa Sungai Meranti. Semua hak dilindungi undang-undang.
                    <span class="text-emerald-400">Dikelola dengan ❤️ untuk masyarakat Indonesia</span>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
