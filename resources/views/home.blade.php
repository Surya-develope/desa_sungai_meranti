@extends('layouts.app')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

<style>
    /* Custom utility to hide scrollbar (for aesthetic) */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }
    
    /* CSS untuk kelas animasi (fade-in dari bawah) */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }
    .animate-on-scroll.is-visible {
        opacity: 1;
        transform: translateY(0);
        transition-delay: var(--delay, 0s); /* Tambahkan delay di CSS */
    }

    /* Menambahkan scroll-snap untuk pengalaman geser yang lebih baik di mobile */
    .snap-x-mandatory {
        scroll-snap-type: x mandatory;
    }
    .snap-start {
        scroll-snap-align: start;
    }
</style>

<nav class="bg-slate-800 text-white shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-start">
        
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa" class="w-12 h-12 rounded-full border-2 border-white object-contain">
            <a href="/">
                <h1 class="text-xl font-extrabold leading-tight tracking-wide uppercase">PEMERINTAH DESA SUNGAI MERANTI</h1>
                <p class="text-sm text-slate-300">Kec. Pinggir Kab. Bengkalis</p>
            </a>
        </div>

        </div>
</nav>

<section class="min-h-[70vh] flex items-center justify-center py-16 px-6 relative" 
    style="background-image: url('{{ asset('images/gambar-desa.png') }}'); background-size: cover; background-position: center;">
    
    <div class="absolute inset-0 bg-slate-900 opacity-40"></div>
    
    <div class="container mx-auto relative z-10 text-center bg-white/40 backdrop-blur-sm p-8 md:p-12 rounded-3xl shadow-2xl border border-white/60 max-w-5xl transition-all duration-500">
        
        <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa Sungai Meranti" class="w-24 h-24 rounded-full shadow-xl ring-4 ring-white p-2 mx-auto mb-6 object-contain animate-on-scroll" data-delay="0">
        
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-slate-800 mb-2 animate-on-scroll" 
            style="text-shadow: 0px 0px 4px rgba(255,255,255,0.8);" data-delay="100">
            SELAMAT DATANG DI SISTEM INFORMASI<br>
            <span class="text-white-600 text-5xl md:text-6xl">DESA SUNGAI MERANTI</span>
        </h1>
        
        <p class="text-xl md:text-2xl font-bold text-gray-700 mb-6 animate-on-scroll" style="text-shadow: 1px 1px 2px rgba(255,255,255,0.5);" data-delay="200">
            Kec. Pinggir, Kab. Bengkalis
        </p>

        <div class="flex overflow-x-auto py-2 md:py-0 md:flex-row justify-start md:justify-center md:space-x-4 space-x-3 px-4 md:px-0 scrollbar-hide snap-x-mandatory">

            <a href="/profil" class="flex-shrink-0 w-36 md:w-56 bg-white/95 p-6 rounded-2xl shadow-xl border-b-4 border-slate-300 text-center hover:bg-white transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl animate-on-scroll snap-start" data-delay="400">
                <div class="text-3xl md:text-5xl text-blue-700 mb-2">🌳</div>
                <p class="text-sm md:text-lg font-bold text-gray-800">Profil Desa</p>
            </a>
            
            <a href="/warga/pengajuan" class="flex-shrink-0 w-36 md:w-56 bg-white/95 p-6 rounded-2xl shadow-xl border-b-4 border-yellow-500 text-center hover:bg-white transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl animate-on-scroll snap-start" data-delay="500">
                <div class="text-3xl md:text-5xl text-yellow-600 mb-2">✍️</div>
                <p class="text-sm md:text-lg font-bold text-gray-800 leading-snug">Administrasi Layanan Surat Online</p>
            </a>
            
            <a href="/surat/tracking" class="flex-shrink-0 w-36 md:w-56 bg-white/95 p-6 rounded-2xl shadow-xl border-b-4 border-red-500 text-center hover:bg-white transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl animate-on-scroll snap-start" data-delay="600">
                <div class="text-3xl md:text-5xl text-red-600 mb-2">🔍</div>
                <p class="text-sm md:text-lg font-bold text-gray-800">Tracking Surat</p>
            </a>

            <a href="/data/penduduk" class="flex-shrink-0 w-36 md:w-56 bg-white/95 p-6 rounded-2xl shadow-xl border-b-4 border-blue-500 text-center hover:bg-white transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl animate-on-scroll snap-start" data-delay="600">
                <div class="text-3xl md:text-5xl text-blue-600 mb-2">📊</div>
                <p class="text-sm md:text-lg font-bold text-gray-800">Data Penduduk</p>
            </a>
            
        </div>
        
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        // Observer untuk animasi Fade-in (animate-on-scroll)
        const scrollObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = parseInt(entry.target.getAttribute('data-delay') || '0');
                    entry.target.style.setProperty('--delay', `${delay / 1000}s`); // Set delay CSS
                    
                    setTimeout(() => {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }, delay);
                }
            });
        }, observerOptions);

        // Ambil semua elemen dengan kelas 'animate-on-scroll'
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        animatedElements.forEach(element => {
            scrollObserver.observe(element);
        });
    });
</script>

<footer class="bg-slate-900 text-white pt-12 pb-8"> 
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="space-y-4">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                        <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa" class="w-8 h-8 object-contain">
                    </div>
                    <h3 class="text-2xl font-bold text-yellow-400">Sungai Meranti</h3>
                </div>
                
                <p class="text-sm text-slate-300">
                    Pusat Informasi dan Layanan Publik Desa Sungai Meranti, Kec. Pinggir, Kab. Bengkalis.
                </p>
                
                <div class="space-y-2 text-sm">
                    <p>
                        <span class="font-bold text-yellow-500">Alamat:</span> Jl. Utama Desa Sungai Meranti No. 1
                    </p>
                    <p>
                        <span class="font-bold text-yellow-500">Telepon:</span> (0765) XXX-XXX
                    </p>
                    <p>
                        <span class="font-bold text-yellow-500">Email:</span> desasuangaimeranti@email.com
                    </p>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4 border-b-2 border-slate-700 pb-1">Layanan Utama</h4>
                <ul class="space-y-2 text-sm text-slate-300">
                    <li><a href="/warga/pengajuan" class="hover:text-yellow-400 transition">Permintaan Surat Online</a></li>
                    <li><a href="/profil" class="hover:text-yellow-400 transition">Profil Desa</a></li>
                    <li><a href="/data/penduduk" class="hover:text-yellow-400 transition">Data Penduduk</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4 border-b-2 border-slate-700 pb-1">Informasi & Kontak</h4>
                <ul class="space-y-2 text-sm text-slate-300">
                    <li><a href="/surat/tracking" class="hover:text-yellow-400 transition">Lacak Surat (Tracking)</a></li>
                    <li><a href="/pengaduan" class="hover:text-yellow-400 transition">Kotak Pengaduan</a></li>
                    <li><a href="/informasi/anggaran" class="hover:text-yellow-400 transition">Informasi Anggaran</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4 border-b-2 border-slate-700 pb-1">Lokasi Kantor Desa</h4>
                <div class="bg-slate-800 h-40 rounded-lg flex items-center justify-center text-slate-500 italic border border-slate-700">
                    *Placeholder Peta Kantor Desa*
                </div>
            </div>

        </div>
        
        <div class="mt-12 pt-6 border-t border-slate-700 text-center text-sm text-slate-500">
            &copy; 2025 Pemerintah Desa Sungai Meranti. Hak Cipta Dilindungi.
        </div>
    </div>
</footer>

@endsection