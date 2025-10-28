@extends('layout.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Pengajuan Surat</h1>

        <div class="mb-4">
            <button id="btn-create" class="bg-green-700 text-white font-bold py-2 px-4 rounded hover:bg-green-800 transition duration-300">
                + Buat Pengajuan Baru
            </button>
        </div>

        <table class="w-full border border-gray-300 rounded-md">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-left">Jenis Surat</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Tanggal</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody id="pengajuan-table-body">
                <tr><td colspan="4" class="text-center py-4">Loading data...</td></tr>
            </tbody>
        </table>

        <div id="notification" class="hidden p-4 mt-4 rounded-md"></div>
    </div>
</div>

<script>
document.getElementById('btn-create').addEventListener('click', function() {
    window.location.href = '/pengajuan/create';
});

async function fetchPengajuan() {
    const tbody = document.getElementById('pengajuan-table-body');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Loading data...</td></tr>';

    try {
        const response = await fetch('/pengajuan', {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load data');
        }

        if (result.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Tidak ada pengajuan.</td></tr>';
            return;
        }

        tbody.innerHTML = '';
        result.data.forEach(pengajuan => {
            const tr = document.createElement('tr');

            const jenisSurat = pengajuan.jenis ? pengajuan.jenis.nama_surat : '-';
            const tanggal = pengajuan.tanggal_pengajuan;
            const status = pengajuan.status;

            const statusMap = {
                'menunggu': 'Menunggu',
                'disetujui': 'Disetujui',
                'ditolak': 'Ditolak',
                'dibatalkan': 'Dibatalkan',
                'menunggu_verifikasi': 'Menunggu Verifikasi'
            };
            const statusText = statusMap[status] || status;

            tr.innerHTML = `
                <td class="border border-gray-300 px-4 py-2">${jenisSurat}</td>
                <td class="border border-gray-300 px-4 py-2">${tanggal}</td>
                <td class="border border-gray-300 px-4 py-2">${statusText}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <button class="btn-detail bg-blue-600 text-white px-3 py-1 rounded mr-2" data-id="${pengajuan.id}">Detail</button>
                    ${status === 'menunggu' ? `<button class="btn-cancel bg-red-600 text-white px-3 py-1 rounded" data-id="${pengajuan.id}">Batal</button>` : ''}
                </td>
            `;

            tbody.appendChild(tr);
        });

        // Attach event listeners for detail and cancel buttons
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                window.location.href = `/pengajuan/${id}`;
            });
        });

        document.querySelectorAll('.btn-cancel').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                if (confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')) {
                    await cancelPengajuan(id);
                }
            });
        });

    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-red-600">Error: ${error.message}</td></tr>`;
    }
}

async function cancelPengajuan(id) {
    try {
        const response = await fetch(`/pengajuan/${id}/batal`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal membatalkan pengajuan');
        }

        showNotification('Pengajuan berhasil dibatalkan', 'success');
        fetchPengajuan();
    } catch (error) {
        showNotification('Error: ' + error.message, 'error');
    }
}

function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `p-4 mt-4 rounded-md ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
    notification.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', fetchPengajuan);
</script>
@endsection
@endsection
