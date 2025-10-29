@extends('layout.app')

@section('title', 'Masuk - Desa Sungai Meranti')

@section('content')
<div class="min-h-screen bg-green-900 flex items-center justify-center pt-20" style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('{{ asset('Desa-teluk-Meranti-1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container mx-auto px-8">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-5xl font-bold text-white mb-4">Selamat Datang Kembali</h2>
                <p class="text-xl text-green-100">
                    Masuk ke akun Anda untuk mengakses layanan desa
                </p>
            </div>

            <!-- Login Form -->
            <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-10 shadow-2xl border border-white/30">
                <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                    @csrf
                    
                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-sm font-medium text-white mb-2">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" id="nik" name="nik" required maxlength="16" 
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                               placeholder="Masukkan NIK 16 digit" autofocus>
                        @error('nik')
                            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required minlength="6"
                                   class="w-full px-4 py-3 pr-12 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                                   placeholder="Masukkan password">
                            <button type="button" onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/60 hover:text-white">
                                <svg id="toggle-password-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" 
                                   class="w-4 h-4 text-yellow-400 bg-white/10 border-white/20 rounded focus:ring-yellow-400 focus:ring-2">
                            <label for="remember" class="ml-2 text-sm text-white">Ingat saya</label>
                        </div>
                        <a href="#" onclick="showInfo('Info', 'Fitur lupa password akan tersedia soon')" class="text-sm text-yellow-300 hover:text-yellow-200 transition-colors">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" id="loginBtn" class="w-full bg-yellow-400 hover:bg-yellow-300 text-green-900 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="w-6 h-6 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span id="loginBtnText">Masuk ke Dashboard</span>
                            <div id="loginSpinner" class="hidden inline-block ml-2 w-4 h-4 border-2 border-green-900 border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-6">
                        <p class="text-green-100">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-yellow-300 hover:text-yellow-200 font-semibold underline transition-colors">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Quick Links -->
            <div class="text-center mt-10">
                <p class="text-green-200 text-sm">
                    Mengalami masalah? 
                    <a href="#" class="text-yellow-300 hover:text-yellow-200 underline">Hubungi Support</a> 
                    atau 
                    <a href="{{ route('home') }}" class="text-yellow-300 hover:text-yellow-200 underline">Kembali ke Beranda</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nikInput = document.getElementById('nik');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const loginSpinner = document.getElementById('loginSpinner');

    // Enable/disable submit button based on form validation
    function updateSubmitButton() {
        const isValid = nikInput.value.length === 16 && passwordInput.value.length >= 6;
        loginBtn.disabled = !isValid;
    }

    // NIK validation
    nikInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) {
            value = value.substring(0, 16);
        }
        e.target.value = value;
        updateSubmitButton();
    });

    passwordInput.addEventListener('input', updateSubmitButton);

    // Form submission handling
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        loginBtn.disabled = true;
        loginBtnText.textContent = 'Memproses...';
        loginSpinner.classList.remove('hidden');
    });

    // Auto-focus first input
    nikInput.focus();
});

// Password show/hide toggle
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggle-password-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
        `;
    } else {
        passwordInput.type = 'password';
        toggleIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
}
</script>
@endsection