@extends('layout.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Formulir Pengajuan Surat</h1>

        {{-- Notifikasi untuk sukses atau error --}}
        <div id="notification" class="hidden p-4 mb-4 rounded-md"></div>

        <form id="pengajuan-form" enctype="multipart/form-data">
            @csrf
            
            {{-- NIK Pemohon --}}
            <div class="mb-4">
                <label for="nik_pemohon" class="block text-gray-700 font-semibold mb-2">NIK Pemohon</label>
                <input type="text" id="nik_pemohon" name="nik_pemohon" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                <p class="text-sm text-gray-500 mt-1">Masukkan NIK Anda yang terdaftar.</p>
            </div>

            {{-- Jenis Surat --}}
            <div class="mb-4">
                <label for="jenis_surat_id" class="block text-gray-700 font-semibold mb-2">Pilih Jenis Surat</label>
                <select id="jenis_surat_id" name="jenis_surat_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <option value="">-- Pilih salah satu --</option>
                    @foreach($jenisSuratList as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_surat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Data Isian (JSON) --}}
            <div class="mb-4">
                <label for="data_isian" class="block text-gray-700 font-semibold mb-2">Data Isian Tambahan</label>
                <textarea id="data_isian" name="data_isian" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder='Contoh: {"nama_anak": "Budi", "keperluan": "Beasiswa"}'></textarea>
                <p class="text-sm text-gray-500 mt-1">Isi data dalam format JSON. Kunci dan nilai harus sesuai dengan template surat.</p>
            </div>

            {{-- File Persyaratan --}}
            <div class="mb-6">
                <label for="file_syarat" class="block text-gray-700 font-semibold mb-2">Upload File Persyaratan</label>
                <input type="file" id="file_syarat" name="file_syarat[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200">
                <p class="text-sm text-gray-500 mt-1">Anda bisa mengupload lebih dari satu file (misal: KTP, KK). Maks 5MB per file.</p>
            </div>

            {{-- Tombol Submit --}}
            <div>
                <button type="submit" id="submit-button" class="w-full bg-green-700 text-white font-bold py-3 px-4 rounded-md hover:bg-green-800 transition duration-300">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('pengajuan-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const submitButton = document.getElementById('submit-button');
    const notification = document.getElementById('notification');

    // Ubah data_isian menjadi objek jika tidak kosong
    const dataIsianValue = formData.get('data_isian');
    if (dataIsianValue) {
        try {
            const jsonData = JSON.parse(dataIsianValue);
            // Hapus field lama dan tambahkan yang sudah diparsing
            formData.delete('data_isian');
            // Laravel akan handle ini dengan benar jika dikirim sebagai array
            for (const key in jsonData) {
                formData.append(`data_isian[${key}]`, jsonData[key]);
            }
        } catch (error) {
            showNotification('Format JSON pada Data Isian tidak valid.', 'error');
            return;
        }
    } else {
        // Jika kosong, kirim sebagai objek kosong
        formData.append('data_isian', {});
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Mengirim...';

    try {
        const response = await fetch('/api/pengajuan', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (!response.ok) {
            // Menampilkan error validasi dari Laravel
            const errorMessages = Object.values(result.errors).flat().join('<br>');
            throw new Error(errorMessages || result.message);
        }

        showNotification('Pengajuan berhasil dikirim! ID Pengajuan Anda: ' + result.data.id, 'success');
        form.reset();
    } catch (error) {
        showNotification('Terjadi kesalahan: ' + error.message, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Kirim Pengajuan';
    }
});

function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.innerHTML = message;
    notification.className = `p-4 mb-4 rounded-md ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
    notification.classList.remove('hidden');
}
</script>
@endsection