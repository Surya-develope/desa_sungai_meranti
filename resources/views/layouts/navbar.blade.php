<nav class="bg-gradient-to-r from-green-700 via-blue-700 to-green-700 shadow-md py-4 px-6">
  <div class="container mx-auto flex items-center justify-between">
    <!-- Logo dan Nama Desa -->
    <div class="flex items-center space-x-4">
      <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa Sungai Meranti" class="w-14 h-14 rounded-md shadow-lg bg-white p-1">
      <div>
        <h1 class="text-white text-lg font-bold leading-tight tracking-wide">PEMERINTAH DESA</h1>
        <h2 class="text-yellow-300 text-2xl font-extrabold -mt-1">SUNGAI MERANTI</h2>
      </div>
    </div>

    <!-- Navigasi -->
    <div class="hidden md:flex space-x-6 text-white font-medium">
      <a href="#" class="hover:text-yellow-300 transition">Beranda</a>
      <a href="#" class="hover:text-yellow-300 transition">Profil Desa</a>
      <a href="#" class="hover:text-yellow-300 transition">Layanan Surat</a>
      <a href="#" class="hover:text-yellow-300 transition">Data Penduduk</a>
      <a href="#" class="hover:text-yellow-300 transition">Kontak</a>
    </div>

    <!-- Tombol Menu Mobile -->
    <button class="md:hidden text-white focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
    </button>
  </div>
</nav>
