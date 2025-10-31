@extends('layout.admin.dashboard')

@section('title', 'Kelola Pengajuan - Desa Sungai Meranti')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Pengajuan</h1>
                <p class="mt-2 text-gray-600">Kelola semua pengajuan surat dari warga</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="refreshData()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filter</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui_verifikasi">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="menunggu_tanda_tangan">Menunggu Tanda Tangan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <input type="text" id="searchInput" placeholder="Cari nama atau NIK..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilters()" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total</dt>
                            <dd class="text-lg font-semibold text-gray-900" id="total-count">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Menunggu</dt>
                            <dd class="text-lg font-semibold text-yellow-700" id="pending-count">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Disetujui</dt>
                            <dd class="text-lg font-semibold text-green-700" id="approved-count">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ditolak</dt>
                            <dd class="text-lg font-semibold text-red-700" id="rejected-count">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Table -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Daftar Pengajuan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="pengajuan-table-body">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-gray-500">Memuat data...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentFilters = {};

async function loadPengajuan(page = 1) {
    const tbody = document.getElementById('pengajuan-table-body');
    tbody.innerHTML = `
        <tr>
            <td colspan="6" class="px-6 py-12 text-center">
                <div class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-500">Memuat data...</span>
                </div>
            </td>
        </tr>
    `;

    try {
        const params = new URLSearchParams({
            page: page,
            ...currentFilters
        });

        const response = await fetch(`/admin/pengajuan?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load pengajuan');
        }

        const result = await response.json();
        
        if (result.success) {
            displayPengajuan(result.data);
            updateStatistics(result.data);
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-red-500">
                        Error: ${result.message}
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error loading pengajuan:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-red-500">
                    Gagal memuat data
                </td>
            </tr>
        `;
    }
}

function displayPengajuan(data) {
    const tbody = document.getElementById('pengajuan-table-body');
    
    if (data.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    Belum ada pengajuan
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = data.data.map(pengajuan => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #${pengajuan.id}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div>
                    <div class="text-sm font-medium text-gray-900">${pengajuan.pemohon?.nama || 'Unknown'}</div>
                    <div class="text-sm text-gray-500">NIK: ${pengajuan.nik_pemohon}</div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${pengajuan.jenis?.nama_surat || 'Unknown'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(pengajuan.tanggal_pengajuan).toLocaleDateString('id-ID')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(pengajuan.status)}">
                    ${getStatusLabel(pengajuan.status)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <a href="/admin/pengajuan/${pengajuan.id}"
                       class="text-blue-600 hover:text-blue-900 text-xs">
                        Lihat Detail
                    </a>
                    ${getActionButtons(pengajuan)}
                </div>
            </td>
        </tr>
    `).join('');
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

function getActionButtons(pengajuan) {
    let buttons = '';
    
    switch(pengajuan.status) {
        case 'menunggu':
            buttons += `
                <button onclick="approvePengajuan(${pengajuan.id})"
                        class="text-green-600 hover:text-green-900 text-xs mr-2">
                    Setujui
                </button>
                <button onclick="rejectPengajuan(${pengajuan.id})"
                        class="text-red-600 hover:text-red-900 text-xs mr-2">
                    Tolak
                </button>
            `;
            break;
        case 'disetujui_verifikasi':
            buttons += `
                <button onclick="generateSurat(${pengajuan.id})"
                        class="text-blue-600 hover:text-blue-900 text-xs mr-2">
                    Generate Surat
                </button>
            `;
            break;
        case 'menunggu_tanda_tangan':
            if (pengajuan.surat_terbit) {
                buttons += `
                    <a href="/${pengajuan.surat_terbit.file_surat}" target="_blank"
                       class="text-purple-600 hover:text-purple-900 text-xs mr-2">
                        Lihat Surat
                    </a>
                `;
            }
            break;
    }
    
    return buttons;
}

function updateStatistics(data) {
    const allPengajuan = data.data;
    
    document.getElementById('total-count').textContent = allPengajuan.length;
    document.getElementById('pending-count').textContent = allPengajuan.filter(p => p.status === 'menunggu').length;
    document.getElementById('approved-count').textContent = allPengajuan.filter(p => p.status === 'disetujui_verifikasi').length;
    document.getElementById('rejected-count').textContent = allPengajuan.filter(p => p.status === 'ditolak').length;
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    currentFilters = {};
    if (status) currentFilters.status = status;
    if (search) currentFilters.search = search;
    
    loadPengajuan(1);
}

function refreshData() {
    currentFilters = {};
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchInput').value = '';
    loadPengajuan(1);
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
            loadPengajuan();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error approving pengajuan:', error);
        alert('Terjadi kesalahan saat menyetujui pengajuan');
    }
}

async function rejectPengajuan(id) {
    const alasan = prompt('Masukkan alasan penolakan:');
    if (!alasan || alasan.trim() === '') {
        alert('Alasan penolakan harus diisi');
        return;
    }

    try {
        const response = await fetch(`/admin/pengajuan/${id}/reject`, {
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
            loadPengajuan();
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
            loadPengajuan();
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
    loadPengajuan();
});
</script>
@endsection