@extends('layout.app')

@section('title', 'Ajukan Surat - Desa Sungai Meranti')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('administrasi') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2">Administrasi</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Ajukan Surat</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Page Header -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-8">
                <div class="px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Ajukan Surat</h1>
                            <p class="mt-2 text-lg text-gray-600">Lengkapi formulir di bawah untuk mengajukan surat administratif</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('pengajuan.create.post') }}" enctype="multipart/form-data" class="space-y-8" id="pengajuanForm">
                @csrf

                <!-- Letter Type Selection -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">1. Pilih Jenis Surat</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div>
                            <label for="jenis_surat_id" class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <select id="jenis_surat_id" name="jenis_surat_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Pilih jenis surat...</option>
                                @foreach($jenisSuratList as $jenis)
                                    <option value="{{ $jenis->id }}" {{ request('jenis') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_surat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_surat_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Applicant Information -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">2. Data Pemohon</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_pemohon" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="nama_pemohon" name="nama_pemohon" required 
                                       value="{{ old('nama_pemohon', $user->nama ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Nama lengkap sesuai KTP">
                                @error('nama_pemohon')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nik_pemohon" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                <input type="text" id="nik_pemohon" name="nik_pemohon" required maxlength="16"
                                       value="{{ old('nik_pemohon', $user->nik ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="16 digit NIK">
                                @error('nik_pemohon')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" required
                                       value="{{ old('tempat_lahir') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Tempat lahir">
                                @error('tempat_lahir')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                                       value="{{ old('tanggal_lahir') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('tanggal_lahir')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="3" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                          placeholder="Alamat lengkap sesuai KTP">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                                @error('alamat')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                                <input type="tel" id="no_hp" name="no_hp" required
                                       value="{{ old('no_hp', $user->no_hp ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="08xxxxxxxxxx">
                                @error('no_hp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                <input type="text" id="pekerjaan" name="pekerjaan" required
                                       value="{{ old('pekerjaan') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Pekerjaan">
                                @error('pekerjaan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">3. Informasi Tambahan</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan / Keperluan</label>
                            <textarea id="keterangan" name="keterangan" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Jelaskan keperluan atau keterangan tambahan...">{{ old('keterangan') }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">Berikan penjelasan singkat mengenai tujuan pengajuan surat ini.</p>
                            @error('keterangan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- File Uploads -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">4. Upload Dokumen Persyaratan</h2>
                        <p class="mt-1 text-sm text-gray-500">Upload dokumen yang diperlukan (PDF, JPG, PNG - max 2MB per file)</p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <!-- KTP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">KTP / Kartu Tanda Penduduk</label>
                                <input type="file" name="ktp" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="mt-1 text-sm text-gray-500">Scan/Foto KTP asli</p>
                                @error('ktp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- KK -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">KK / Kartu Keluarga</label>
                                <input type="file" name="kk" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="mt-1 text-sm text-gray-500">Scan/Foto KK</p>
                                @error('kk')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dokumen Tambahan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Lainnya (Opsional)</label>
                                <input type="file" name="dokumen_lainnya" accept=".pdf,.jpg,.jpeg,.png" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <p class="mt-1 text-sm text-gray-500">Dokumen pendukung lainnya jika diperlukan</p>
                                @error('dokumen_lainnya')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">5. Konfirmasi</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="agree_terms" name="agree_terms" type="checkbox" required
                                       class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="agree_terms" class="font-medium text-gray-700">
                                    Saya menyetujui syarat dan ketentuan pengajuan surat
                                </label>
                                <p class="text-gray-500">Dengan mencentang ini, Anda menyatakan bahwa semua data yang diisi adalah benar dan dokumen yang diunggah adalah asli.</p>
                            </div>
                        </div>
                        @error('agree_terms')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('administrasi') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" id="submitBtn" 
                            class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span id="submitBtnText">Ajukan Surat</span>
                        <div id="submitSpinner" class="hidden ml-2 w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pengajuanForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitSpinner = document.getElementById('submitSpinner');

    // NIK validation
    const nikInput = document.getElementById('nik_pemohon');
    nikInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) {
            value = value.substring(0, 16);
        }
        e.target.value = value;
    });

    // Phone number validation
    const phoneInput = document.getElementById('no_hp');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        e.target.value = value;
    });

    // File upload validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('File terlalu besar. Maksimal ukuran file adalah 2MB.');
                    e.target.value = '';
                }
            }
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtnText.textContent = 'Memproses...';
        submitSpinner.classList.remove('hidden');
    });
});
</script>
@endsection