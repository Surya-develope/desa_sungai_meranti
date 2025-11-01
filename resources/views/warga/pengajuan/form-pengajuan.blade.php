@extends('layout.app')

@section('title', 'Ajukan Surat - Desa Sungai Meranti')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
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
                <input type="hidden" name="keterangan" id="keteranganField" value="">

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

                <!-- Dynamic Applicant Information -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">2. Data Pemohon</h2>
                        <p class="mt-1 text-sm text-gray-500">Form akan menyesuaikan berdasarkan jenis surat yang dipilih</p>
                    </div>
                    <div class="px-6 py-6">
                        <div id="dynamicFields" class="space-y-6">
                            <!-- Dynamic input fields will be inserted here -->
                            <div id="initialState" class="text-center py-12">
                                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Siap Mengajukan Surat</h3>
                                <p class="text-gray-500 mb-4">Pilih jenis surat di atas untuk melihat form pengajuan yang sesuai</p>
                                <div class="text-sm text-gray-400">
                                    <p>• Form akan menyesuaikan dengan jenis surat yang dipilih</p>
                                    <p>• Data Anda akan otomatis terisi jika sudah login</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Uploads -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">3. Upload Dokumen Persyaratan</h2>
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
                        <h2 class="text-lg font-medium text-gray-900">4. Konfirmasi</h2>
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
    const jenisSuratSelect = document.getElementById('jenis_surat_id');

    // Function to create different field types
    function createField(field) {
        const wrapper = document.createElement('div');

        // Create label
        const label = document.createElement('label');
        label.setAttribute('for', field.key);
        label.className = 'block text-sm font-medium text-gray-700 mb-2';
        label.textContent = field.label || field.name || field.key;
        wrapper.appendChild(label);

        // Create input based on field type
        let input;
        
        switch(field.type) {
            case 'date':
                input = document.createElement('input');
                input.type = 'date';
                break;
            case 'number':
                input = document.createElement('input');
                input.type = 'number';
                input.step = 'any';
                break;
            case 'email':
                input = document.createElement('input');
                input.type = 'email';
                break;
            case 'tel':
                input = document.createElement('input');
                input.type = 'tel';
                break;
            case 'textarea':
                input = document.createElement('textarea');
                input.rows = '3';
                break;
            case 'select':
                input = document.createElement('select');
                // Add default option for select
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = `Pilih ${field.label || field.name || field.key}...`;
                input.appendChild(defaultOption);
                break;
            default:
                input = document.createElement('input');
                input.type = 'text';
                break;
        }

        // Ensure field has key property
        if (!field.key && field.name) {
            field.key = field.name;
        }
        if (!field.key) {
            field.key = 'field_' + Math.random().toString(36).substr(2, 9);
        }

        // Set common properties
        input.id = field.key;
        input.name = `data_pemohon[${field.key}]`;
        input.className = 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent';
        input.placeholder = `Masukkan ${field.label || field.name || field.key}`;
        input.required = true;

        // Add specific attributes for different field types
        if (field.type === 'email') {
            input.pattern = '[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$';
        }

        if (field.type === 'number') {
            const keyLower = (field.key || '').toLowerCase();
            if (keyLower.includes('nik')) {
                input.pattern = '[0-9]{16}';
                input.maxLength = 16;
                input.placeholder = 'Masukkan NIK 16 digit';
            }
        }

        // Add name-specific enhancements
        const keyLower = (field.key || '').toLowerCase();
        if (keyLower.includes('nama')) {
            input.placeholder = 'Masukkan nama lengkap';
        } else if (keyLower.includes('alamat')) {
            input.placeholder = 'Masukkan alamat lengkap';
        } else if (keyLower.includes('pekerjaan')) {
            input.placeholder = 'Masukkan pekerjaan';
        } else if (keyLower.includes('hp') || keyLower.includes('telepon')) {
            input.placeholder = 'Masukkan nomor HP';
        }

        wrapper.appendChild(input);

        // Add helper text for specific fields
        if (keyLower.includes('nik')) {
            const helpText = document.createElement('p');
            helpText.className = 'mt-1 text-xs text-gray-500';
            helpText.textContent = 'NIK harus 16 digit';
            wrapper.appendChild(helpText);
        }

        return wrapper;
    }
    
    // Function to load dynamic fields
    function loadDynamicFields(jenisSuratId) {
        const dynamicFieldsContainer = document.getElementById('dynamicFields');
        dynamicFieldsContainer.innerHTML = '';

        if (!jenisSuratId) {
            // Restore initial state
            dynamicFieldsContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Jenis Surat</h3>
                    <p class="text-gray-500">Silakan pilih jenis surat di atas untuk melihat form pengajuan</p>
                </div>
            `;
            return;
        }

        // Show loading state
        dynamicFieldsContainer.innerHTML = `
            <div class="text-center py-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Memuat Form Pengajuan</h3>
                    <p class="text-blue-700">Sedang menyiapkan form untuk jenis surat yang dipilih...</p>
                </div>
            </div>
        `;

        // Use the correct API endpoint
        fetch(`/api/jenis-surat/${jenisSuratId}/placeholders`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data);
                let fields = data.data || [];
                
                // Handle the case where data might be a JSON string
                if (typeof fields === 'string') {
                    try {
                        fields = JSON.parse(fields);
                    } catch (e) {
                        console.log('Error parsing fields JSON:', e);
                        fields = [];
                    }
                }
                
                console.log('Processed fields:', fields);
                
                // Clear loading state
                dynamicFieldsContainer.innerHTML = '';
                
                // Add form header
                const formHeader = document.createElement('div');
                formHeader.className = 'bg-green-50 border border-green-200 rounded-lg p-4 mb-6';
                formHeader.innerHTML = `
                    <h3 class="text-lg font-semibold text-green-800 mb-2">Form Pengajuan</h3>
                    <p class="text-green-700 text-sm">Field count: ${fields.length} - Isi form berikut sesuai dengan data yang diperlukan untuk jenis surat yang dipilih</p>
                `;
                dynamicFieldsContainer.appendChild(formHeader);

                if (fields.length === 0) {
                    // Add message for no specific fields
                    const noFieldsMsg = document.createElement('div');
                    noFieldsMsg.className = 'bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4';
                    noFieldsMsg.innerHTML = `
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Data yang Diajukan</h3>
                            <p class="text-gray-600">Jenis surat ini tidak memerlukan data pemohon tambahan. Data akan diisi berdasarkan informasi yang sudah ada.</p>
                        </div>
                    `;
                    dynamicFieldsContainer.appendChild(noFieldsMsg);
                }

                // Add dynamic fields in a grid
                const fieldsContainer = document.createElement('div');
                fieldsContainer.className = 'grid grid-cols-1 md:grid-cols-2 gap-6';
                
                fields.forEach((field, index) => {
                    console.log(`Creating field ${index}:`, field);
                    const fieldElement = createField(field);
                    fieldsContainer.appendChild(fieldElement);
                });
                
                dynamicFieldsContainer.appendChild(fieldsContainer);
                
                // Auto-fill user data after dynamic fields are added
                if (typeof fillFields === 'function') {
                    console.log('Auto-filling user data');
                    setTimeout(fillFields, 100);
                }

                // Add separator and common fields
                const separator = document.createElement('hr');
                separator.className = 'my-6 border-gray-300';
                dynamicFieldsContainer.appendChild(separator);

                const commonHeader = document.createElement('h4');
                commonHeader.className = 'text-md font-semibold text-gray-700 mb-4';
                commonHeader.textContent = 'Keterangan Pengajuan';
                dynamicFieldsContainer.appendChild(commonHeader);

                const commonFields = [
                    { key: 'keterangan', label: 'Keperluan/Tujuan Surat', type: 'textarea', placeholder: 'Jelaskan kedatangan atau tujuan pembuatan surat ini' }
                ];

                const commonFieldsContainer = document.createElement('div');
                commonFieldsContainer.className = 'grid grid-cols-1 gap-6';
                commonFields.forEach(field => {
                    const fieldDiv = createField(field);
                    const textarea = fieldDiv.querySelector('textarea');
                    if (textarea) {
                        textarea.value = '';
                        textarea.placeholder = field.placeholder || textarea.placeholder;
                        textarea.required = true;
                        textarea.name = 'keterangan'; // Special handling for keterengan field
                    }
                    commonFieldsContainer.appendChild(fieldDiv);
                });
                dynamicFieldsContainer.appendChild(commonFieldsContainer);

            })
            .catch(error => {
                console.error('Error fetching form structure:', error);
                dynamicFieldsContainer.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-800 text-sm">Error: ${error.message}</p>
                        <button type="button" onclick="if(typeof window.loadDynamicFields === 'function') { window.loadDynamicFields(document.getElementById('jenis_surat_id').value); } else { location.reload(); }"
                                class="mt-2 text-red-600 underline hover:text-red-800">Coba lagi</button>
                    </div>
                `;
            });
    }
    
    // Make loadDynamicFields globally accessible for button click
    window.loadDynamicFields = loadDynamicFields;

    // Event listener for jenis_surat change
    jenisSuratSelect.addEventListener('change', function() {
        loadDynamicFields(this.value);
    });

    // Auto-load fields if jenis is pre-selected (when coming from jenis-surat page)
    if (jenisSuratSelect.value && jenisSuratSelect.value !== '') {
        // Only load if it's a valid selection
        setTimeout(() => {
            if (jenisSuratSelect.value && jenisSuratSelect.value !== '') {
                loadDynamicFields(jenisSuratSelect.value);
            }
        }, 100);
    }
    // Otherwise, keep the initial state as is - no loading spinner

    // Auto-fill user data for logged-in users
    function autoFillUserData() {
        @if(isset($user))
        const userData = {
            'nama_lengkap': '{{ $user->nama ?? '' }}',
            'nik': '{{ $user->nik ?? '' }}',
            'alamat': '{{ $user->alamat ?? '' }}',
            'no_hp': '{{ $user->no_hp ?? '' }}',
            'tempat_lahir': '{{ $user->tempat_lahir ?? '' }}',
            'tanggal_lahir': '{{ $user->tanggal_lahir ?? '' }}',
            'pekerjaan': '{{ $user->pekerjaan ?? '' }}'
        };
        
        // Function to actually fill the fields
        function fillFields() {
            Object.keys(userData).forEach(key => {
                if (userData[key]) {
                    const input = document.querySelector(`[name="data_pemohon[${key}]"]`);
                    if (input && !input.value) {
                        input.value = userData[key];
                    }
                }
            });
        }
        @else
        function fillFields() {
            // No user data available
        }
        @endif
        
        return fillFields;
    }

    // Create the fill function
    const fillFields = autoFillUserData();

    // Form submission
    form.addEventListener('submit', function(e) {
        // Sync keterengan field from dynamic textarea to hidden field
        const keterenganTextarea = document.querySelector('textarea[name="keterangan"]');
        const keterenganHidden = document.getElementById('keteranganField');
        if (keterenganTextarea && keterenganHidden) {
            keterenganHidden.value = keterenganTextarea.value;
        }

        submitBtn.disabled = true;
        submitBtnText.textContent = 'Memproses...';
        submitSpinner.classList.remove('hidden');
    });
});
</script>
@endsection