@extends('layout.app')

@section('title', 'Kelola Jenis Surat - Desa Sungai Meranti')

@section('content')
@section('content')
<style>
    /* Success Notification Animation */
    #successNotification {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Modal Animation */
    #addModal, #editModal, #previewModal {
        animation: fadeIn 0.2s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Enhanced loading spinner */
    .loading-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
<div class="min-h-screen bg-gray-50 pt-20">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kelola Jenis Surat</h1>
                    <p class="text-sm text-gray-600">Kelola jenis-jenis surat yang tersedia untuk pengajuan</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="toggleModal('addModal')" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Jenis Surat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Filters -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="px-6 py-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchInput" placeholder="Cari jenis surat..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="flex gap-2">
                        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        <button onclick="clearFilters()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Jenis</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-count">{{ count($jenisSuratList) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Aktif</p>
                        <p class="text-2xl font-semibold text-green-700" id="active-count">{{ $jenisSuratList->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Nonaktif</p>
                        <p class="text-2xl font-semibold text-red-700" id="inactive-count">{{ $jenisSuratList->where('is_active', false)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Terbaru</p>
                        <p class="text-2xl font-semibold text-yellow-700">{{ $jenisSuratList->where('created_at', '>=', now()->subDays(7))->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('nama_surat')">
                                Nama Surat
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('is_active')">
                                Status
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('created_at')">
                                Dibuat
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="jenisSuratTable">
                        @forelse($jenisSuratList as $jenis)
                            <tr class="hover:bg-gray-50" data-id="{{ $jenis->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="selected[]" value="{{ $jenis->id }}" class="row-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <button type="button" class="text-left group focus:outline-none" onclick="previewJenisSurat({{ $jenis->id }})">
                                                <div class="text-sm font-medium text-gray-900 group-hover:text-green-600 transition-colors">
                                                    {{ $jenis->nama_surat }}
                                                </div>
                                                <div class="text-xs text-gray-500 group-hover:text-gray-700">Klik untuk melihat template</div>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $jenis->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $jenis->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $jenis->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ \Carbon\Carbon::parse($jenis->created_at)->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs">{{ \Carbon\Carbon::parse($jenis->created_at)->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="editJenisSurat({{ $jenis->id }})" 
                                                class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button onclick="toggleStatus({{ $jenis->id }})" 
                                                class="text-{{ $jenis->is_active ? 'red' : 'green' }}-600 hover:text-{{ $jenis->is_active ? 'red' : 'green' }}-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                            </svg>
                                        </button>
                                        <button onclick="deleteJenisSurat({{ $jenis->id }})" 
                                                class="text-red-600 hover:text-red-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Jenis Surat</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan jenis surat pertama</p>
                                    <button onclick="toggleModal('addModal')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Tambah Jenis Surat
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div id="bulkActions" class="hidden fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-lg border border-gray-200 p-4">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700" id="selectedCount">0 dipilih</span>
                <button onclick="bulkToggleStatus()" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                    Toggle Status
                </button>
                <button onclick="bulkDelete()" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                    Hapus
                </button>
                <button onclick="clearSelection()" class="px-3 py-1 text-sm bg-gray-600 text-white rounded hover:bg-gray-700">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Notification -->
<div id="successNotification" class="hidden fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-md shadow-lg">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium" id="successMessage">Jenis surat berhasil ditambahkan!</p>
        </div>
        <div class="ml-auto pl-3">
            <button onclick="hideSuccessNotification()" class="text-green-400 hover:text-green-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Jenis Surat</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form Column -->
                <div>
                    <form id="addForm" onsubmit="submitAddForm(event)" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Surat</label>
                            <input type="text" name="nama_surat" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Template</label>
                            <input type="file" name="file_template" required class="w-full text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100" accept=".doc,.docx,.pdf,.odt">
                            <p class="text-xs text-gray-500 mt-1">Pilih file template untuk melihat preview contoh surat</p>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="closeAddModal()" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                            <button type="submit" id="submitBtn" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="submit-text">Simpan</span>
                                <span class="loading-text hidden inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Preview Column -->
                <div>
                    <div class="border border-gray-200 rounded-md p-4 bg-gray-50">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Preview Contoh Surat</h4>
                        <div id="templatePreview" class="min-h-[400px] bg-white border border-gray-200 rounded-md flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm">Pilih file template untuk melihat preview</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Template akan dikonversi ke PDF untuk preview
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Jenis Surat</h3>
            <form id="editForm" onsubmit="submitEditForm(event)">
                <input type="hidden" name="id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Surat</label>
                    <input type="text" name="nama_surat" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="toggleModal('editModal')" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
    <div class="relative bg-white max-w-4xl w-full mx-4 rounded-lg shadow-xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <div>
                <h3 class="text-lg font-semibold text-gray-900" id="previewTitle">Pratinjau Template</h3>
                <p class="text-sm text-gray-500">Dokumen template akan ditampilkan di bawah jika formatnya didukung.</p>
            </div>
            <button type="button" onclick="closePreview()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-4">
            <iframe id="previewFrame" class="w-full h-[520px] border border-gray-200 rounded-md hidden" src="" title="Preview Template"></iframe>
            <div id="previewFallback" class="hidden mt-4 text-sm text-gray-600">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Pratinjau Terbatas</h3>
                            <p class="mt-2 text-sm text-yellow-700">
                                Format template <span id="fileExtension" class="font-mono bg-yellow-100 px-1 rounded"></span> tidak dapat ditampilkan secara langsung di browser.
                                Gunakan tombol <span class="font-semibold text-yellow-800">Unduh Template</span> untuk membuka dokumen di aplikasi yang sesuai.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50 rounded-b-lg">
            <p class="text-xs text-gray-500">Jika pratinjau tidak muncul, silakan unduh dokumen dan buka secara manual.</p>
            <a id="downloadTemplateLink" href="#" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                Unduh Template
            </a>
        </div>
    </div>
</div>

<script>
let jenisSuratData = @json($jenisSuratList);
let currentSort = { field: null, direction: 'asc' };
const storageBaseUrl = @json(asset('storage'));
const previewModal = document.getElementById('previewModal');
const previewFrame = document.getElementById('previewFrame');
const previewFallback = document.getElementById('previewFallback');
const downloadTemplateLink = document.getElementById('downloadTemplateLink');
const previewTitle = document.getElementById('previewTitle');

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search and Filter
    document.getElementById('searchInput')?.addEventListener('input', filterTable);
    document.getElementById('statusFilter')?.addEventListener('change', filterTable);

    // Select all checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        updateBulkActions();
    });

    // Template file change event
    document.querySelector('input[name="file_template"]')?.addEventListener('change', handleTemplateFileChange);

    // Row checkboxes
    document.addEventListener('change', (event) => {
        if (!event.target.classList.contains('row-checkbox')) return;
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        document.getElementById('selectAll').checked = checked.length === checkboxes.length && checkboxes.length > 0;
        updateBulkActions();
    });

    // Modal click outside to close
    if (previewModal) {
        previewModal.addEventListener('click', (event) => {
            if (event.target === previewModal) {
                closePreview();
            }
        });
    }
});

function handleTemplateFileChange(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('templatePreview');
    
    if (!file) {
        resetPreview();
        return;
    }

    // Show loading state
    previewContainer.innerHTML = `
        <div class="flex items-center justify-center py-20">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mx-auto mb-4"></div>
                <p class="text-sm text-gray-500">Memproses template...</p>
            </div>
        </div>
    `;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('preview_only', 'true');

    // Create temporary preview
    const reader = new FileReader();
    reader.onload = function(e) {
        setTimeout(() => {
            const extension = file.name.split('.').pop()?.toLowerCase();
            
            if (extension === 'pdf') {
                // For PDF files, show inline preview
                const blob = new Blob([e.target.result], { type: 'application/pdf' });
                const url = URL.createObjectURL(blob);
                previewContainer.innerHTML = `
                    <div class="h-full w-full">
                        <iframe src="${url}" class="w-full h-[500px] border-0" title="Preview PDF"></iframe>
                    </div>
                `;
            } else if (['doc', 'docx'].includes(extension)) {
                // For Word documents, show message with download option
                previewContainer.innerHTML = `
                    <div class="flex items-center justify-center py-20">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Template Word</h4>
                            <p class="text-xs text-gray-500 mb-3">File ${file.name} akan dikonversi ke PDF secara otomatis</p>
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                <p class="text-xs text-blue-700">
                                    Template ini akan diproses setelah disimpan dan dapat dilihat di preview surat.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // For other file types
                previewContainer.innerHTML = `
                    <div class="flex items-center justify-center py-20">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">${file.name}</h4>
                            <p class="text-xs text-gray-500">Format ${extension?.toUpperCase()} - akan tersedia sebagai template</p>
                        </div>
                    </div>
                `;
            }
        }, 1000);
    };
    
    reader.readAsArrayBuffer(file);
}

function resetPreview() {
    const previewContainer = document.getElementById('templatePreview');
    previewContainer.innerHTML = `
        <div class="text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm">Pilih file template untuk melihat preview</p>
        </div>
    `;
}

function showSuccessNotification(message = 'Jenis surat berhasil ditambahkan!') {
    const notification = document.getElementById('successNotification');
    const messageElement = document.getElementById('successMessage');
    
    messageElement.textContent = message;
    notification.classList.remove('hidden');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideSuccessNotification();
    }, 5000);
}

function hideSuccessNotification() {
    const notification = document.getElementById('successNotification');
    notification.classList.add('hidden');
}

function closeAddModal() {
    toggleModal('addModal');
    resetPreview();
    document.getElementById('addForm').reset();
    setSubmitButtonState(false);
}

function setSubmitButtonState(loading) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = submitBtn.querySelector('.submit-text');
    const loadingText = submitBtn.querySelector('.loading-text');
    
    if (loading) {
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');
    } else {
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        loadingText.classList.add('hidden');
    }
}

function filterTable() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || '';
    
    const rows = document.querySelectorAll('#jenisSuratTable tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const statusText = row.querySelector('.rounded-full')?.textContent.toLowerCase();
        
        const matchesSearch = text.includes(searchTerm);
        const matchesStatus = !statusFilter || (statusFilter === '1' ? statusText.includes('aktif') : statusText.includes('nonaktif'));
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterTable();
}

function sortTable(field) {
    if (currentSort.field === field) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.field = field;
        currentSort.direction = 'asc';
    }
    
    // Implement sorting logic here
    console.log('Sorting by', field, 'direction:', currentSort.direction);
}

function toggleModal(modalId) {
    document.getElementById(modalId).classList.toggle('hidden');
}

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checkedBoxes.length > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = `${checkedBoxes.length} dipilih`;
    } else {
        bulkActions.classList.add('hidden');
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.row-checkbox, #selectAll');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    updateBulkActions();
}

function editJenisSurat(id) {
    const item = jenisSuratData.find(data => data.id == id);
    if (item) {
        document.querySelector('#editForm [name="id"]').value = item.id;
        document.querySelector('#editForm [name="nama_surat"]').value = item.nama_surat;
        document.querySelector('#editForm [name="deskripsi"]').value = item.deskripsi || '';
        toggleModal('editModal');
    }
}

function toggleStatus(id) {
    if (confirm('Yakin ingin mengubah status jenis surat ini?')) {
        fetch(`/api/admin/jenis-surat/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        }).then(() => location.reload());
    }
}

function deleteJenisSurat(id) {
    if (confirm('Yakin ingin menghapus jenis surat ini? Tindakan ini tidak dapat dibatalkan.')) {
        fetch(`/api/admin/jenis-surat/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        }).then(() => location.reload());
    }
}

async function submitAddForm(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        alert('CSRF token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
        return;
    }

    // Set loading state
    setSubmitButtonState(true);

    try {
        const response = await fetch('{{ url('/admin/jenis-surat') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const responseData = await response.json();

        if (!response.ok) {
            throw new Error(responseData.message || responseData.errors?.nama_surat || 'Gagal menyimpan jenis surat');
        }

        // Show success notification
        showSuccessNotification(responseData.message || 'Jenis surat berhasil ditambahkan!');
        
        // Close modal and reset form
        closeAddModal();
        
        // Reload page after short delay to show updated data
        setTimeout(() => {
            location.reload();
        }, 1500);

    } catch (error) {
        console.error('Error:', error);
        alert('Error: ' + error.message);
        setSubmitButtonState(false); // Reset loading state on error
    }
}

async function submitEditForm(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const id = formData.get('id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!id) {
        alert('Data jenis surat tidak ditemukan.');
        return;
    }

    if (!csrfToken) {
        alert('CSRF token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
        return;
    }

    try {
        const response = await fetch(`{{ url('/admin/jenis-surat') }}/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            }
        });

        const responseData = await response.json();

        if (!response.ok) {
            throw new Error(responseData.message || 'Gagal memperbarui jenis surat');
        }

        showSuccessNotification('Jenis surat berhasil diperbarui!');
        toggleModal('editModal');
        form.reset();
        setTimeout(() => location.reload(), 1500);
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    }
}

function bulkToggleStatus() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    if (confirm(`Yakin ingin mengubah status ${checkedBoxes.length} item ini?`)) {
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        fetch('/api/admin/jenis-surat/bulk-toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ ids })
        }).then(() => location.reload());
    }
}

function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    if (confirm(`Yakin ingin menghapus ${checkedBoxes.length} item ini? Tindakan ini tidak dapat dibatalkan.`)) {
        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        fetch('/api/admin/jenis-surat/bulk-delete', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ ids })
        }).then(() => location.reload());
    }
}

function resolveTemplateUrl(path) {
    if (!path) return null;
    
    // Check if path already contains URL
    if (path.startsWith('http')) {
        return path;
    }
    
    // Extract filename from path
    const filename = path.split('/').pop();
    return `/admin/templates/${filename}`;
}

function resolvePreviewConfig(path) {
    if (!path) {
        return { previewUrl: null, downloadUrl: null };
    }

    const downloadUrl = resolveTemplateUrl(path);
    if (!downloadUrl) {
        return { previewUrl: null, downloadUrl: null };
    }

    const extension = path.split('.').pop()?.toLowerCase() || '';
    const officeExtensions = ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'odt', 'ods', 'odp'];

    if (extension === 'pdf') {
        return { previewUrl: downloadUrl, downloadUrl };
    }

    if (officeExtensions.includes(extension)) {
        const encoded = encodeURIComponent(window.location.origin + downloadUrl);
        return {
            previewUrl: `https://view.officeapps.live.com/op/embed.aspx?src=${encoded}`,
            downloadUrl
        };
    }

    return { previewUrl: null, downloadUrl };
}

function previewJenisSurat(id) {
    const item = jenisSuratData.find(data => data.id == id);

    if (!item) {
        alert('Data jenis surat tidak ditemukan.');
        return;
    }

    if (!item.file_template) {
        alert('Template belum tersedia untuk jenis surat ini.');
        return;
    }

    const { previewUrl, downloadUrl } = resolvePreviewConfig(item.file_template);

    previewTitle.textContent = item.nama_surat;
    previewFrame.classList.add('hidden');
    previewFallback.classList.add('hidden');
    previewFrame.src = '';

    // Show file extension in fallback message
    const extension = item.file_template.split('.').pop()?.toLowerCase() || '';
    document.getElementById('fileExtension').textContent = extension.toUpperCase();

    if (previewUrl) {
        console.log('Preview URL:', previewUrl); // Debug log
        previewFrame.src = previewUrl;
        
        // Try to load preview, show fallback if it fails
        previewFrame.onload = function() {
            previewFrame.classList.remove('hidden');
        };
        
        previewFrame.onerror = function() {
            console.log('Preview failed, showing fallback');
            previewFrame.classList.add('hidden');
            previewFallback.classList.remove('hidden');
        };
        
        // Show iframe initially, will hide if error occurs
        setTimeout(() => {
            if (previewFrame.src) {
                previewFrame.classList.remove('hidden');
            }
        }, 1000);
        
    } else {
        previewFallback.classList.remove('hidden');
    }

    if (downloadUrl) {
        console.log('Download URL:', downloadUrl); // Debug log
        downloadTemplateLink.href = downloadUrl;
        downloadTemplateLink.target = '_blank';
    } else {
        downloadTemplateLink.removeAttribute('href');
    }

    previewModal.classList.remove('hidden');
}

function closePreview() {
    previewFrame.src = '';
    previewModal.classList.add('hidden');
}
</script>
@endsection