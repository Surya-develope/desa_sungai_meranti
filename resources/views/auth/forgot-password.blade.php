@extends('layout.app')

@section('title', 'Lupa Password - Desa Sungai Meranti')

@section('content')
<div class="min-h-screen bg-green-900 flex items-center justify-center pt-20" style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('{{ asset('Desa-teluk-Meranti-1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container mx-auto px-8">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-5xl font-bold text-white mb-4">Lupa Password?</h2>
                <p class="text-xl text-green-100">
                    Masukkan email Anda untuk mendapatkan link reset password
                </p>
            </div>

            <!-- Forgot Password Form -->
            <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-10 shadow-2xl border border-white/30">
                @if(session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('forgot-password.post') }}" class="space-y-6" id="forgotPasswordForm">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all"
                               placeholder="nama@email.com" autofocus>
                        @error('email')
                            <p class="text-red-300 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" id="submitBtn" class="w-full bg-yellow-400 hover:bg-yellow-300 text-green-900 py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-6 h-6 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span id="submitBtnText">Kirim Link Reset</span>
                            <div id="submitSpinner" class="hidden inline-block ml-2 w-4 h-4 border-2 border-green-900 border-t-transparent rounded-full animate-spin"></div>
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="text-center pt-6">
                        <p class="text-green-100">
                            Ingat password Anda? 
                            <a href="{{ route('login') }}" class="text-yellow-300 hover:text-yellow-200 font-semibold underline transition-colors">
                                Kembali ke Login
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="text-center mt-10">
                <p class="text-green-200 text-sm">
                    Tidak menerima email? 
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
    const form = document.getElementById('forgotPasswordForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitSpinner = document.getElementById('submitSpinner');

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtnText.textContent = 'Mengirim...';
        submitSpinner.classList.remove('hidden');
    });
});
</script>
@endsection