@extends('layout.app')

@section('title', 'Tracking Surat - Desa Sungai Meranti')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Tracking Status Surat</h1>
                <p class="text-lg text-gray-600">Masukkan nomor pengajuan untuk melihat status surat Anda</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
                <div class="px-6 py-6">
                    <form method="GET" action="{{ route('tracking') }}" class="flex gap-4">
                        <div class="flex-1">
                            <label for="pengajuan_id" class="block text-sm font-medium text-gray-700 mb-2">Nomor Pengajuan</label>
                            <input type="text" id="pengajuan_id" name="id" 
                                   value="{{ request('id') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Masukkan nomor pengajuan (contoh: #123)">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cek Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search Results -->
            @if(request('id'))
                @if(isset($pengajuan))
                    <!-- Status Card -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
                        <div class="px-6 py-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">Status Pengajuan #{{ $pengajuan->id }}</h2>
                                @php
                                    $statusConfig = [
                                        'menunggu' => ['bg-yellow-100 text-yellow-800', 'Menunggu'],
                                        'menunggu_verifikasi' => ['bg-blue-100 text-blue-800', 'Menunggu Verifikasi'],
                                        'disetujui' => ['bg-green-100 text-green-800', 'Disetujui'],
                                        'ditolak' => ['bg-red-100 text-red-800', 'Ditolak'],
                                        'dibatalkan' => ['bg-gray-100 text-gray-800', 'Dibatalkan'],
                                    ];
                                    $status = $pengajuan->status ?? 'menunggu';
                                    $config = $statusConfig[$status] ?? ['bg-gray-100 text-gray-800', ucwords(str_replace('_', ' ', $status))];
                                @endphp
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $config[0] }}">
                                    {{ $config[1] }}
                                </span>
                            </div>

                            <!-- Progress Steps -->
                            <div class="mb-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Pengajuan Dibuat</p>
                                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i') }}</p>
                                    </div>
                                </div>

                                <div class="ml-4 flex items-center pt-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 {{ in_array($pengajuan->status, ['menunggu_verifikasi', 'disetujui']) ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 {{ in_array($pengajuan->status, ['menunggu_verifikasi', 'disetujui']) ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <p class="text-sm font-medium {{ in_array($pengajuan->status, ['menunggu_verifikasi', 'disetujui']) ? 'text-gray-900' : 'text-gray-500' }}">Dalam Verifikasi</p>
                                        <p class="text-sm text-gray-500">
                                            @if(in_array($pengajuan->status, ['menunggu_verifikasi', 'disetujui']))
                                                Sedang diproses oleh admin
                                            @else
                                                Menunggu proses verifikasi
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="ml-4 flex items-center pt-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 {{ $pengajuan->status === 'disetujui' ? 'bg-green-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 {{ $pengajuan->status === 'disetujui' ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <p class="text-sm font-medium {{ $pengajuan->status === 'disetujui' ? 'text-gray-900' : 'text-gray-500' }}">Disetujui</p>
                                        <p class="text-sm text-gray-500">
                                            @if($pengajuan->status === 'disetujui')
                                                Surat telah disetujui
                                            @else
                                                Menunggu persetujuan
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Informasi Pengajuan</h3>
                                    <dl class="space-y-1">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Jenis Surat:</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ $pengajuan->jenis_surat->nama_surat ?? '-' }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Tanggal Pengajuan:</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Pemohon:</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ $pengajuan->user->nama ?? '-' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Informasi Tambahan</h3>
                                    @if($pengajuan->keterangan)
                                        <div class="mb-2">
                                            <dt class="text-sm text-gray-600">Keterangan:</dt>
                                            <dd class="text-sm text-gray-900">{{ $pengajuan->keterangan }}</dd>
                                        </div>
                                    @endif
                                    @if($pengajuan->status === 'ditolak' && $pengajuan->alasan_penolakan)
                                        <div class="mb-2">
                                            <dt class="text-sm text-red-600">Alasan Penolakan:</dt>
                                            <dd class="text-sm text-red-900">{{ $pengajuan->alasan_penolakan }}</dd>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('warga.pengajuan.show', $pengajuan) }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Detail Lengkap
                        </a>
                        <a href="{{ route('warga.dashboard') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Dashboard Saya
                        </a>
                    </div>
                @else
                    <!-- Not Found -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nomor Pengajuan Tidak Ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Pastikan nomor pengajuan yang Anda masukkan benar.</p>
                            <div class="mt-6">
                                <a href="{{ route('tracking') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Coba Lagi
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Instructions -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-8">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Cara Melacak Status Surat</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-2xl mx-auto">
                                Anda dapat melacak status pengajuan surat menggunakan nomor pengajuan yang diberikan saat mengajukan surat.
                            </p>
                        </div>

                        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-lg font-semibold text-green-600">1</span>
                                </div>
                                <h4 class="mt-3 text-sm font-medium text-gray-900">Catat Nomor Pengajuan</h4>
                                <p class="mt-1 text-sm text-gray-500">Nomor pengajuan diberikan setelah Anda mengajukan surat</p>
                            </div>
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-lg font-semibold text-green-600">2</span>
                                </div>
                                <h4 class="mt-3 text-sm font-medium text-gray-900">Masukkan Nomor</h4>
                                <p class="mt-1 text-sm text-gray-500">Masukkan nomor pengajuan di kolom pencarian di atas</p>
                            </div>
                            <div class="text-center">
                                <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-lg font-semibold text-green-600">3</span>
                                </div>
                                <h4 class="mt-3 text-sm font-medium text-gray-900">Lihat Status</h4>
                                <p class="mt-1 text-sm text-gray-500">Status akan ditampilkan beserta detail prosesnya</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto focus search input when page loads
    document.getElementById('pengajuan_id').focus();
    
    // Remove # from input if user types it
    document.getElementById('pengajuan_id').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace('#', '');
    });
});
</script>
@endsection