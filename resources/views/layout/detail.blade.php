@extends('layout.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Detail Pengajuan Surat</h1>

        <div id="pengajuan-detail" class="mb-6">
            <p>Loading data...</p>
        </div>

        <div id="notification" class="hidden p-4 mt-4 rounded-md"></div>
    </div>
</div>

<script>
async function fetchPengajuanDetail() {
    const detailDiv = document.getElementById('pengajuan-detail');
    const notification = document.getElementById('notification');
    notification.classList.add('hidden');
    detailDiv.innerHTML = '<p>Loading data...</p>';

    const urlParts = window.location.pathname.split('/');
    const id = urlParts[urlParts.length - 1];

    try {
        const response = await fetch(`/pengajuan/${id}`, {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load data');
        }

        const pengajuan = result.data;
        const dataIsian = pengajuan.data_isian || {};
        const status = pengajuan.status;
        const alasanPenolakan = pengajuan.alasan_penolakan || '';
        const suratTerbit = pengajuan.surat_terbit || pengajuan.suratTerbit || null;

        let html = '<h2 class="text-xl font-semibold mb-4">Data Isian Warga</h2><ul class="list-disc list-inside mb-4">';
        for (const [key, value] of Object.entries(dataIsian)) {
            if (typeof value === 'object') {
                html += `<li><strong>${key}:</strong> ${JSON.stringify(value)}</li>`;
            } else {
                html += `<li><strong>${key}:</strong> ${value}</li>`;
            }
        }
        html += '</ul>';

        html += `<p class="mb-4"><strong>Status:</strong> ${status.charAt(0).toUpperCase() + status.slice(1)}</p>`;

        if (status === 'disetujui' && suratTerbit && suratTerbit.file_surat) {
            const fileUrl = `/storage/${suratTerbit.file_surat.replace(/^public\//, '')}`;
            html += `<a href="${fileUrl}" download class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">Download Surat</a>`;
        } else if (status === 'ditolak' && alasanPenolakan) {
            html += `<p class="text-red-600"><strong>Alasan Penolakan:</strong> ${alasanPenolakan}</p>`;
        }

        detailDiv.innerHTML = html;
    } catch (error) {
        detailDiv.innerHTML = `<p class="text-red-600">Error: ${error.message}</p>`;
    }
}

document.addEventListener('DOMContentLoaded', fetchPengajuanDetail);
</script>
@endsection