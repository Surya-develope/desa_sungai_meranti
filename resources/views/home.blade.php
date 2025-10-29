@extends('layout.app')

@section('title', 'Beranda - Desa Sungai Meranti')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center bg-green-900 pt-0" style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('{{ asset('Desa-teluk-Meranti-1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <!-- Background Overlay -->
    <div class="absolute inset-0 bg-green-900/30"></div>
    <div class="absolute inset-0 bg-green-900/20"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-10 -right-10 w-96 h-96 bg-green-300/10 rounded-full animate-pulse"></div>
        <div class="absolute top-1/2 -left-20 w-80 h-80 bg-emerald-300/15 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 right-1/3 w-64 h-64 bg-lime-300/10 rounded-full animate-pulse delay-1000"></div>
    </div>

    <div class="container mx-auto px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="text-center lg:text-left">
                <h2 class="text-6xl lg:text-8xl font-bold text-white mb-10 leading-tight tracking-tight">
                    Administrasi Desa<br>
                    <span class="text-emerald-300 relative">
                        Digital Terdepan
                        <div class="absolute -bottom-2 left-0 w-full h-1 bg-emerald-400/50 rounded-full"></div>
                    </span>
                </h2>
                
                <p class="text-2xl lg:text-3xl text-green-100 mb-12 leading-relaxed max-w-3xl font-light">
                    Melayani masyarakat dengan sistem informasi yang modern, transparan, dan mudah diakses.
                    Semua layanan administrasi desa dalam satu platform digital terdepan.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 justify-center lg:justify-start">
                    <a href="{{ route('administrasi') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white px-8 py-5 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-2xl hover:shadow-emerald-500/30 flex items-center justify-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Administrasi Online
                    </a>
                    <a href="https://desasungaimeranti.id/" target="_blank" class="bg-green-700 hover:bg-green-600 text-white px-8 py-5 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-2xl hover:shadow-green-600/30 flex items-center justify-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Web Profil
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block">
                <div class="bg-white/15 backdrop-blur-xl rounded-3xl p-10 shadow-2xl border border-white/30 hover:bg-white/20 transition-all duration-300">
                    <h3 class="text-3xl font-bold text-white mb-8 text-center">Statistik Desa</h3>
                    <div class="grid grid-cols-2 gap-8">
                        <div class="text-center group">
                            <div class="text-6xl font-black text-emerald-300 mb-2 group-hover:scale-110 transition-transform" data-count="2847">0</div>
                            <div class="text-green-100 font-medium text-lg">Total Penduduk</div>
                        </div>
                        <div class="text-center group">
                            <div class="text-6xl font-black text-emerald-300 mb-2 group-hover:scale-110 transition-transform" data-count="756">0</div>
                            <div class="text-green-100 font-medium text-lg">Kepala Keluarga</div>
                        </div>
                        <div class="text-center group">
                            <div class="text-6xl font-black text-emerald-300 mb-2 group-hover:scale-110 transition-transform" data-count="23">0</div>
                            <div class="text-green-100 font-medium text-lg">Dusun</div>
                        </div>
                        <div class="text-center group">
                            <div class="text-6xl font-black text-emerald-300 mb-2 group-hover:scale-110 transition-transform" data-count="1">0</div>
                            <div class="text-green-100 font-medium text-lg">Desa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
        <div class="animate-bounce">
            <svg class="w-8 h-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('[data-count]');
    const speed = 200;

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-count');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        updateCount();
    });
});
</script>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>

@endsection
