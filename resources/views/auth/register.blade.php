@extends('layout.app')

@section('title', 'Daftar - Desa Sungai Meranti')

@section('content')
<div class="min-h-screen bg-green-900 flex items-start pt-20" style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('{{ asset('Desa-teluk-Meranti-1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container mx-auto px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-5xl font-bold text-white mb-4">Bergabung dengan Kami</h2>
                <p class="text-xl text-green-100 max-w-lg mx-auto">
                    Daftar sebagai warga desa untuk mengakses semua layanan digital administrasi desa.
                </p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-10 shadow-2xl border border-white/30">
                <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                    @csrf
                    
                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-white mb-2">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" id="nik" name="nik" required maxlength="16" 
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                               placeholder="Masukkan NIK 16 digit">
                        @error('nik')
                            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-white mb-2">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" required 
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                               placeholder="Masukkan nama lengkap">
                        @error('nama')
                            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                               placeholder="nama@email.com">
                        @error('email')
                            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                        <input type="password" id="password" name="password" required minlength="6"
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                               placeholder="Minimal 6 karakter">
                        @error('password')
                            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Code (Optional) -->
                    <div>
                        <label for="kode_rahasia" class="block text-sm font-medium text-white mb-2">Kode Rahasia Admin <span class="text-yellow-300">(Opsional)</span></label>
                        <input type="password" id="kode_rahasia" name="kode_rahasia" 
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                               placeholder="Kosongkan untuk warga biasa">
                        <p class="text-sm text-green-200 mt-1">Isi jika Anda adalah staf/admin desa</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-300 text-green-900 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-xl">
                            <svg class="w-6 h-6 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Daftar Sekarang
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-6">
                        <p class="text-green-100">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-yellow-300 hover:text-yellow-200 font-semibold underline transition-colors">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-10">
                <p class="text-green-200">
                    Dengan mendaftar, Anda setuju dengan 
                    <a href="#" class="text-yellow-300 hover:text-yellow-200 underline">Syarat & Ketentuan</a> 
                    dan 
                    <a href="#" class="text-yellow-300 hover:text-yellow-200 underline">Kebijakan Privasi</a> 
                    kami.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-focus first input
document.getElementById('nik').focus();

// NIK validation
document.getElementById('nik').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 16) {
        value = value.substring(0, 16);
    }
    e.target.value = value;
});
</script>
@endsection