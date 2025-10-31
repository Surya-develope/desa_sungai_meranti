@extends('layout.admin.dashboard')

@section('title', 'Detail Pengajuan - Desa Sungai Meranti')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <a href="/admin/pengajuan" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pengajuan</h1>
                <p class="mt-2 text-gray-600">Detail pengajuan surat dari warga</p>
            </div>
        </div>
    </div>

    <div id="loading" class="text-center py-12">
        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-400 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-500">Memuat data...</p>
    </div>

    <div id="pengajuan-detail" class="hidden">
        <!-- Header Info -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Pengajuan #<span id="pengajuan-id">-</span>
                    </h2>
                    <span id="status-badge" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">
                        -
                    </span>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Informasi Pemohon</h3>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium">Nama:</span> <span id="pemohon-nama">-</span></p>
                            <p class="text-sm"><span class="font-medium">NIK:</span> <span id="pemohon-nik">-</span></p>
                            <p class="text-sm"><span class="font-medium">Alamat:</span> <span id="pemohon-alamat">-</span></p>
                            <p class="text-sm"><span class="font-medium">No. HP:</span> <span id="pemohon-hp">-</span></p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Informasi Surat</h3>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium">Jenis Surat:</span> <span id="jenis-surat">-</span></p>
                            <p class="text-sm"><span class="font-medium">Tanggal Pengajuan:</span> <span id="tanggal-pengajuan">-</span></p>
                            <p class="text-sm"><span class="font-medium">Status:</span> <span id="status-text">-</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Isian -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Data Pengajuan</h3>
            </div>
            <div class="px-6 py-4">
                <div id="data-isian-content">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>

        <!-- File Requirements -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Dokumen Persyaratan</h3>
            </div>
            <div class="px-6 py-4">
                <div id="file-requirements-content">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
            </div>
            <div class="px-6 py-4">
                <div class="flex space-x-4" id="action-buttons">
                    <!-- Action buttons will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejection-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pengajuan</h3>
            <form id="rejection-form">
                <div class="mb-4">
                    <label for="alasan-penolakan" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                    <textarea id="alasan-penolakan" name="alasan" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                              placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectionModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPengajuanId = window.location.pathname.split('/').pop();
let currentPengajuanData = null;

async function loadPengajuanDetail() {
    try {
        const response = await fetch(`/admin/pengajuan/${currentPengajuanId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load pengajuan detail');
        }

        const result = await response.json();
        
        if (result.success) {
            currentPengajuanData = result.data;
            displayPengajuanDetail(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error loading pengajuan detail:', error);
        document.getElementById('loading').innerHTML = `
            <div class="text-red-500">
                <p>Error: ${error.message}</p>
                <button onclick="loadPengajuanDetail()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Coba Lagi
                </button>
            </div>
        `;
    }
}

function displayPengajuanDetail(pengajuan) {
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('pengajuan-detail').classList.remove('hidden');

    // Header info
    document.getElementById('pengajuan-id').textContent = pengajuan.id;
    document.getElementById('pemohon-nama').textContent = pengajuan.pemohon?.nama || 'Unknown';
    document.getElementById('pemohon-nik').textContent = pengajuan.nik_pemohon;
    document.getElementById('pemohon-alamat').textContent = pengajuan.pemohon?.alamat || 'Unknown';
    document.getElementById('pemohon-hp').textContent = pengajuan.pemohon?.no_hp || 'Unknown';
    document.getElementById('jenis-surat').textContent = pengajuan.jenis?.nama_surat || 'Unknown';
    document.getElementById('tanggal-pengajuan').textContent = new Date(pengajuan.tanggal_pengajuan).toLocaleDateString('id-ID');
    
    // Status
    const statusBadge = document.getElementById('status-badge');
    statusBadge.textContent = getStatusLabel(pengajuan.status);
    statusBadge.className = `inline-flex px-3 py-1 text-sm font-semibold rounded-full ${getStatusColor(pengajuan.status)}`;
    document.getElementById('status-text').textContent = getStatusLabel(pengajuan.status);

    // Data isian
    displayDataIsian(pengajuan.data_isian);
    
    // File requirements
    displayFileRequirements(pengajuan.file_syarat);
    
    // Action buttons
    displayActionButtons(pengajuan);
}

function displayDataIsian(dataIsian) {
    const container = document.getElementById('data-isian-content');
    
    if (!dataIsian || typeof dataIsian !== 'object') {
        container.innerHTML = '<p class="text-gray-500">Tidak ada data isian</p>';
        return;
    }

    let html = '';
    
    if (dataIsian.data_pemohon) {
        html += '<h4 class="font-medium text-gray-900 mb-3">Data Pemohon</h4>';
        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">';
        Object.entries(dataIsian.data_pemohon).forEach(([key, value]) => {
            if (value) {
                html += `
                    <div>
                        <label class="block text-sm font-medium text-gray-500 capitalize">
                            ${key.replace('_', ' ')}
                        </label>
                        <p class="text-sm text-gray-900">${value}</p>
                    </div>
                `;
            }
        });
        html += '</div>';
    }
    
    if (dataIsian.keterangan) {
        html += `
            <h4 class="font-medium text-gray-900 mb-3">Keperluan</h4>
            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">${dataIsian.keterangan}</p>
        `;
    }
    
    container.innerHTML = html;
}

function displayFileRequirements(files) {
    const container = document.getElementById('file-requirements-content');
    
    if (!files || !Array.isArray(files) || files.length === 0) {
        container.innerHTML = '<p class="text-gray-500">Tidak ada dokumen yang diupload</p>';
        return;
    }

    const html = files.map(file => `
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3">
            <div>
                <p class="text-sm font-medium text-gray-900">${file.name}</p>
                <p class="text-xs text-gray-500">Size: ${file.size_kb || 'Unknown'} KB</p>
            </div>
            <div class="flex space-x-2">
                <a href="/${file.path}" target="_blank" 
                   class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat
                </a>
                <a href="/${file.path}" download 
                   class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded hover:bg-green-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download
                </a>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

function displayActionButtons(pengajuan) {
    const container = document.getElementById('action-buttons');
    let html = '';

    switch(pengajuan.status) {
        case 'menunggu':
            html = `
                <button onclick="approvePengajuan(${pengajuan.id})"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Setujui
                </button>
                <button onclick="openRejectionModal()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak
                </button>
            `;
            break;
        case 'disetujui_verifikasi':
            html = `
                <button onclick="generateSurat(${pengajuan.id})"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Generate Surat
                </button>
            `;
            break;
        case 'menunggu_tanda_tangan':
            if (pengajuan.surat_terbit) {
                html = `
                    <a href="/${pengajuan.surat_terbit.file_surat}" target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Surat
                    </a>
                `;
            }
            break;
    }

    if (html) {
        container.innerHTML = html;
    } else {
        container.innerHTML = '<p class="text-gray-500">Tidak ada aksi yang tersedia</p>';
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'menunggu':
            return 'bg-yellow-100 text-yellow-800';
        case 'disetujui_verifikasi':
            return 'bg-blue-100 text-blue-800';
        case 'ditolak':
            return 'bg-red-100 text-red-800';
        case 'menunggu_tanda_tangan':
            return 'bg-purple-100 text-purple-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function getStatusLabel(status) {
    switch(status) {
        case 'menunggu':
            return 'Menunggu';
        case 'disetujui_verifikasi':
            return 'Disetujui';
        case 'ditolak':
            return 'Ditolak';
        case 'menunggu_tanda_tangan':
            return 'Menunggu Tanda Tangan';
        default:
            return status;
    }
}

function openRejectionModal() {
    document.getElementById('rejection-modal').classList.remove('hidden');
}

function closeRejectionModal() {
    document.getElementById('rejection-modal').classList.add('hidden');
    document.getElementById('rejection-form').reset();
}

async function approvePengajuan(id) {
    if (!confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/pengajuan/${id}/approve`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();
        
        if (result.success) {
            alert('Pengajuan berhasil disetujui!');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error approving pengajuan:', error);
        alert('Terjadi kesalahan saat menyetujui pengajuan');
    }
}

async function rejectPengajuan(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const alasan = formData.get('alasan');

    try {
        const response = await fetch(`/admin/pengajuan/${currentPengajuanId}/reject`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ alasan })
        });

        const result = await response.json();
        
        if (result.success) {
            alert('Pengajuan berhasil ditolak!');
            closeRejectionModal();
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error rejecting pengajuan:', error);
        alert('Terjadi kesalahan saat menolak pengajuan');
    }
}

async function generateSurat(id) {
    if (!confirm('Apakah Anda yakin ingin generate surat untuk pengajuan ini?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/pengajuan/${id}/generate`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();
        
        if (result.success) {
            alert('Surat berhasil di-generate!');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error generating surat:', error);
        alert('Terjadi kesalahan saat generate surat');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadPengajuanDetail();
    document.getElementById('rejection-form').addEventListener('submit', rejectPengajuan);
});
</script>
@endsection