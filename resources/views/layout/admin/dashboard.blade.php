@extends('layout.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Dashboard Admin</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-yellow-100 p-4 rounded shadow text-center">
            <h3 class="text-lg font-semibold">Pengajuan Baru</h3>
            <p id="count-pengajuan-baru" class="text-3xl font-bold">0</p>
        </div>
        <div class="bg-green-100 p-4 rounded shadow text-center">
            <h3 class="text-lg font-semibold">Surat Disetujui</h3>
            <p id="count-surat-disetujui" class="text-3xl font-bold">0</p>
        </div>
        <div class="bg-red-100 p-4 rounded shadow text-center">
            <h3 class="text-lg font-semibold">Surat Ditolak</h3>
            <p id="count-surat-ditolak" class="text-3xl font-bold">0</p>
        </div>
        <div class="bg-blue-100 p-4 rounded shadow text-center">
            <h3 class="text-lg font-semibold">Surat Terbit Hari Ini</h3>
            <p id="count-surat-terbit" class="text-3xl font-bold">0</p>
        </div>
    </div>

    <div class="flex space-x-4 mb-6">
        <a href="{{ route('admin.pengajuan.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Data Pengajuan</a>
        <a href="{{ route('admin.jenis_surat.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Jenis Surat</a>
        <a href="{{ route('admin.surat_terbit.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">Surat Terbit</a>
    </div>

    {{-- Existing pengajuan table --}}
    @include('admin.pengajuan_table')
</div>

<script>
async function fetchDashboardSummary() {
    try {
        const response = await fetch('/admin/dashboard', {
            headers: { 'Accept': 'application/json' }
        });
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load dashboard summary');
        }

        document.getElementById('count-pengajuan-baru').textContent = result.data.jumlah_pengajuan_baru;
        document.getElementById('count-surat-disetujui').textContent = result.data.jumlah_surat_disetujui;
        document.getElementById('count-surat-ditolak').textContent = result.data.jumlah_surat_ditolak;
        document.getElementById('count-surat-terbit').textContent = result.data.jumlah_surat_terbit_hari_ini;
    } catch (error) {
        console.error('Error loading dashboard summary:', error);
    }
}

document.addEventListener('DOMContentLoaded', fetchDashboardSummary);
</script>
@endsection