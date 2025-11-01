@extends('layout.app')

@section('title', 'Administrasi Online - Desa Sungai Meranti')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Administrasi Online</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Pilih jenis surat yang ingin Anda ajukan. Setiap jenis surat memiliki persyaratan dan proses yang berbeda.
            </p>
        </div>

        <!-- Back Button -->
        <div class="mb-8">
            @auth
                <a href="{{ route('warga.dashboard') }}"
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            @endauth
        </div>

        <!-- Letter Types Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($jenisSuratList as $jenis)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-200">
                    <!-- Card Header - Hanya background tanpa icon -->
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-8">
                        <h3 class="text-xl font-bold text-white leading-tight">{{ $jenis->nama_surat }}</h3>
                    </div>

                    <!-- Card Body -->
                    <div class="px-6 py-8">
                        <!-- Gunakan nama jenis surat sebagai deskripsi utama -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $jenis->nama_surat }}</h4>
                            <p class="text-gray-600 leading-relaxed text-sm">
                                {{ $jenis->deskripsi ?? 'Surat administrasi resmi dari Desa Sungai Meranti untuk keperluan Anda.' }}
                            </p>
                        </div>
                        
                        <!-- Info section dengan ruang yang lebih luas -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Status:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Terakhir diperbarui:</span>
                                <span class="text-gray-600">
                                    {{ $jenis->updated_at ? \Carbon\Carbon::parse($jenis->updated_at)->diffForHumans() : 'Baru' }}
                                </span>
                            </div>
                            @if($jenis->file_template)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500">Template:</span>
                                    <span class="text-gray-600 truncate max-w-32"> Tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 pb-6">
                        <a href="{{ route('pengajuan.create') }}?jenis={{ $jenis->id }}" 
                           class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Ajukan Surat Ini
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Jenis Surat</h3>
                        <p class="text-gray-500">Jenis surat untuk administrasi belum tersedia saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Info Section -->
        <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <div class="bg-blue-500 rounded-lg p-2 mt-1">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Informasi Pengajuan</h3>
                    <ul class="text-blue-800 space-y-1 text-sm">
                        <li>• Pastikan Anda telah menyiapkan semua dokumen persyaratan</li>
                        <li>• Proses persetujuan biasanya memakan waktu 1-3 hari kerja</li>
                        <li>• Anda dapat melihat status pengajuan di menu Tracking Surat</li>
                        <li>• Untuk pertanyaan lebih lanjut, hubungi kantor desa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection