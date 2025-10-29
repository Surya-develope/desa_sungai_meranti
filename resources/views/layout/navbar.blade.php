<nav class="bg-gradient-to-r from-green-700 via-blue-700 to-green-700 shadow-md">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between py-3">
      <!-- Logo dan Nama Desa -->
      <a href="{{ route('home') }}" class="flex items-center space-x-3 hover:bg-white/10 rounded-md p-2 transition-all duration-300 cursor-pointer">
        <img src="{{ asset('logo-desa.png') }}" alt="Logo Desa Sungai Meranti" class="w-12 h-12">
        <div>
          <h1 class="text-white text-base font-bold">Pemerintah Desa Sungai Meranti</h1>
          <p class="text-white text-xs">Kec. Pinggir Kab. Bengkalis</p>
        </div>
      </a>
    </div>
  </div>
  <!-- Navigasi Geser -->
  <div class="bg-white/10 backdrop-blur-sm">
    <div class="container mx-auto px-4">
      <div class="flex overflow-x-auto whitespace-nowrap py-2 space-x-4">
        <a href="#" class="text-white hover:bg-green-600/50 rounded-md px-3 py-1 transition-all duration-300">Beranda</a>
        <a href="#" class="text-white hover:bg-green-600/50 rounded-md px-3 py-1 transition-all duration-300">Profil Desa</a>
        <a href="#" class="text-white hover:bg-green-600/50 rounded-md px-3 py-1 transition-all duration-300">Layanan Surat</a>
        <a href="#" class="text-white hover:bg-green-600/50 rounded-md px-3 py-1 transition-all duration-300">Data Penduduk</a>
        <a href="#" class="text-white hover:bg-green-600/50 rounded-md px-3 py-1 transition-all duration-300">Berita Desa</a>
        <a href="#" class="text-white hover:bg-green-600/50 rounded-md px-3 py-1 transition-all duration-300">Galeri</a>
        <a href="#" class="text-white hover:bg-green-600/50 rounded-md px-3 py-1 transition-all duration-300">Kontak</a>
      </div>
    </div>
  </div>
</nav>
