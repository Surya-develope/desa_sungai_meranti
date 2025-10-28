@extends('layout.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Buat Pengajuan Surat Baru</h2>
    <form id="pengajuanForm" method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="jenis_surat_id" class="block font-semibold mb-2">Jenis Surat</label>
            <select id="jenis_surat_id" name="jenis_surat_id" class="w-full border border-gray-300 rounded p-2" required>
                <option value="">Pilih Jenis Surat</option>
                @foreach($jenisSuratList as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->nama_surat }}</option>
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
document.getElementById('jenis_surat_id').addEventListener('change', function() {
    const jenisSuratId = this.value;
    const dynamicFieldsContainer = document.getElementById('dynamicFields');
    dynamicFieldsContainer.innerHTML = '';

    if (!jenisSuratId) return;

    fetch(`/api/jenis-surat/${jenisSuratId}/placeholders`)
        .then(response => response.json())
        .then(fields => {
            fields.forEach(field => {
                const div = document.createElement('div');
                div.classList.add('mb-4');

                const label = document.createElement('label');
                label.setAttribute('for', field.key);
                label.classList.add('block', 'font-semibold', 'mb-2');
                label.textContent = field.label;

                const input = document.createElement('input');
                input.type = 'text';
                input.id = field.key;
                input.name = `data_pemohon[${field.key}]`;
                input.classList.add('w-full', 'border', 'border-gray-300', 'rounded', 'p-2');
                input.required = true;

                div.appendChild(label);
                div.appendChild(input);
                dynamicFieldsContainer.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Error fetching placeholders:', error);
        });
});
</script>
@endsection