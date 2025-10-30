@extends('layout.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Buat Pengajuan Surat Baru</h2>
    <form id="pengajuanForm" method="POST" action="{{ route('pengajuan.create') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="jenis_surat_id" class="block font-semibold mb-2">Jenis Surat</label>
            <select id="jenis_surat_id" name="jenis_surat_id" class="w-full border border-gray-300 rounded p-2" required>
                <option value="">Pilih Jenis Surat</option>
                @foreach($jenisSuratList as $jenis)
                    <option value="{{ $jenis->id }}" {{ (old('jenis_surat_id', $selectedJenisId ?? '') == $jenis->id) ? 'selected' : '' }}>
                        {{ $jenis->nama_surat }}
                    </option>
                @endforeach
            </select>
        </div>
        <div id="dynamicFields" class="mb-4">
            <!-- Dynamic input fields will be inserted here -->
        </div>
        <div class="mb-4">
            <label for="file_syarat" class="block font-semibold mb-2">Upload File Persyaratan</label>
            <input type="file" id="file_syarat" name="file_syarat[]" multiple class="w-full" />
        </div>
        <div class="mb-4">
            <label for="keterangan" class="block font-semibold mb-2">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3" class="w-full border border-gray-300 rounded p-2"></textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ajukan Surat</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratSelect = document.getElementById('jenis_surat_id');
    
    // Function to create different field types
    function createField(field) {
        const div = document.createElement('div');
        div.classList.add('mb-4');

        // Create label
        const label = document.createElement('label');
        label.setAttribute('for', field.key);
        label.classList.add('block', 'font-semibold', 'mb-2');
        label.textContent = field.label || field.name || field.key;
        div.appendChild(label);

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
                input.classList.add('w-full', 'border', 'border-gray-300', 'rounded', 'p-2', 'resize-vertical');
                break;
            default:
                input = document.createElement('input');
                input.type = 'text';
                break;
        }

        // Set common properties
        input.id = field.key;
        input.name = `data_pemohon[${field.key}]`;
        input.classList.add('w-full', 'border', 'border-gray-300', 'rounded', 'p-2');
        input.placeholder = `Masukkan ${field.label || field.name || field.key}`;
        input.required = true;

        // Add specific attributes for different field types
        if (field.type === 'email') {
            input.pattern = '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$';
        }

        if (field.type === 'number') {
            if (field.key.includes('nik')) {
                input.pattern = '[0-9]{16}';
                input.maxLength = 16;
                input.placeholder = 'Masukkan NIK 16 digit';
            }
        }

        // Add name-specific enhancements
        const keyLower = field.key.toLowerCase();
        if (keyLower.includes('nama')) {
            input.placeholder = 'Masukkan nama lengkap';
        } else if (keyLower.includes('alamat')) {
            input.placeholder = 'Masukkan alamat lengkap';
        } else if (keyLower.includes('pekerjaan')) {
            input.placeholder = 'Masukkan pekerjaan';
        } else if (keyLower.includes('hp') || keyLower.includes('telepon')) {
            input.placeholder = 'Masukkan nomor HP';
        }

        div.appendChild(input);

        // Add helper text for specific fields
        if (keyLower.includes('nik')) {
            const helpText = document.createElement('p');
            helpText.classList.add('text-xs', 'text-gray-500', 'mt-1');
            helpText.textContent = 'NIK harus 16 digit';
            div.appendChild(helpText);
        }

        return div;
    }
    
    // Function to load dynamic fields
    function loadDynamicFields(jenisSuratId) {
        const dynamicFieldsContainer = document.getElementById('dynamicFields');
        dynamicFieldsContainer.innerHTML = '';

        if (!jenisSuratId) {
            dynamicFieldsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Silakan pilih jenis surat terlebih dahulu</p>';
            return;
        }

        // Show loading state
        dynamicFieldsContainer.innerHTML = '<div class="text-center py-4"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"></div><p class="text-gray-500 mt-2">Memuat form...</p></div>';

        fetch(`/api/jenis-surat/${jenisSuratId}/placeholders`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const fields = data.data || [];
                
                if (fields.length === 0) {
                    dynamicFieldsContainer.innerHTML = '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4"><p class="text-blue-800 text-sm">Tidak ada field khusus untuk jenis surat ini. Form akan menggunakan field standar.</p></div>';
                    
                    // Add standard fields
                    const standardFields = [
                        { key: 'nama_lengkap', label: 'Nama Lengkap', type: 'text' },
                        { key: 'nik', label: 'NIK', type: 'number' },
                        { key: 'alamat', label: 'Alamat', type: 'textarea' },
                        { key: 'tempat_lahir', label: 'Tempat Lahir', type: 'text' },
                        { key: 'tanggal_lahir', label: 'Tanggal Lahir', type: 'date' },
                        { key: 'jenis_kelamin', label: 'Jenis Kelamin', type: 'text' },
                        { key: 'pekerjaan', label: 'Pekerjaan', type: 'text' },
                        { key: 'no_hp', label: 'Nomor HP', type: 'tel' }
                    ];
                    
                    standardFields.forEach(field => {
                        dynamicFieldsContainer.appendChild(createField(field));
                    });
                    return;
                }

                // Add form header
                const formHeader = document.createElement('div');
                formHeader.classList.add('bg-green-50', 'border', 'border-green-200', 'rounded-lg', 'p-4', 'mb-6');
                formHeader.innerHTML = '<h3 class="text-lg font-semibold text-green-800 mb-2">Form Pengajuan</h3><p class="text-green-700 text-sm">Isi form berikut sesuai dengan data yang diperlukan:</p>';
                dynamicFieldsContainer.appendChild(formHeader);

                // Add dynamic fields
                fields.forEach(field => {
                    dynamicFieldsContainer.appendChild(createField(field));
                });

                // Add separator and common fields
                const separator = document.createElement('hr');
                separator.classList.add('my-6', 'border-gray-300');
                dynamicFieldsContainer.appendChild(separator);

                const commonHeader = document.createElement('h4');
                commonHeader.classList.add('text-md', 'font-semibold', 'text-gray-700', 'mb-4');
                commonHeader.textContent = 'Informasi Tambahan';
                dynamicFieldsContainer.appendChild(commonHeader);

                const commonFields = [
                    { key: 'keperluan', label: 'Keperluan Surat', type: 'textarea', placeholder: 'Jelaskan keperluan atau tujuan pembuatan surat' }
                ];

                commonFields.forEach(field => {
                    dynamicFieldsContainer.appendChild(createField(field));
                });

            })
            .catch(error => {
                console.error('Error fetching placeholders:', error);
                dynamicFieldsContainer.innerHTML = '<div class="bg-red-50 border border-red-200 rounded-lg p-4"><p class="text-red-800 text-sm">Gagal memuat form. Silakan coba lagi.</p></div>';
            });
    }
    
    // Event listener for jenis_surat change
    jenisSuratSelect.addEventListener('change', function() {
        loadDynamicFields(this.value);
    });

    // Auto-load fields if jenis is pre-selected (when coming from jenis-surat page)
    if (jenisSuratSelect.value) {
        // Small delay to ensure the select is fully rendered
        setTimeout(() => {
            loadDynamicFields(jenisSuratSelect.value);
        }, 100);
    }
});
</script>
@endsection