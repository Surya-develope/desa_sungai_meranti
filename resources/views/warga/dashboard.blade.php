@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500">Selamat datang kembali,</p>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Warga</h1>
                <p class="text-sm text-gray-500">Pantau seluruh pengajuan surat yang telah Anda ajukan.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('administrasi') }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-3 rounded-lg transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Administrasi Online
                </a>
                <a href="https://desasungaimeranti.id/" target="_blank" class="inline-flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 text-white font-semibold px-4 py-3 rounded-lg transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Web Profil
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Pengajuan</p>
                <p class="mt-2 text-2xl font-semibold text-gray-800">{{ $summary['total'] }}</p>
            </div>
            <div class="rounded-lg border border-green-200 bg-green-50 p-5 shadow-sm">
                <p class="text-sm text-green-600">Menunggu</p>
                <p class="mt-2 text-2xl font-semibold text-green-700">{{ $summary['menunggu'] }}</p>
            </div>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm text-emerald-600">Disetujui</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-700">{{ $summary['disetujui'] }}</p>
            </div>
            <div class="rounded-lg border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-sm text-rose-600">Ditolak</p>
                <p class="mt-2 text-2xl font-semibold text-rose-700">{{ $summary['ditolak'] }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jenis Surat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal Pengajuan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($pengajuanList as $pengajuan)
                    @php
                        $statusLabels = [
                            'menunggu' => 'Menunggu',
                            'menunggu_verifikasi' => 'Menunggu Verifikasi',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            'dibatalkan' => 'Dibatalkan',
                        ];
                        $statusClasses = [
                            'menunggu' => 'bg-green-100 text-green-700',
                            'menunggu_verifikasi' => 'bg-indigo-100 text-indigo-700',
                            'disetujui' => 'bg-emerald-100 text-emerald-700',
                            'ditolak' => 'bg-rose-100 text-rose-700',
                            'dibatalkan' => 'bg-gray-100 text-gray-600',
                        ];
                        $statusKey = $pengajuan->status ?? '';
                        $statusLabel = $statusLabels[$statusKey] ?? ucwords(str_replace('_', ' ', $statusKey));
                        $statusClass = $statusClasses[$statusKey] ?? 'bg-gray-100 text-gray-700';
                        $tanggalPengajuan = $pengajuan->tanggal_pengajuan
                            ? \Illuminate\Support\Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('d F Y')
                            : '-';
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                            {{ $pengajuan->jenis->nama_surat ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                            {{ $tanggalPengajuan }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('warga.pengajuan.show', $pengajuan) }}"
                                   class="inline-flex items-center rounded-md border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100 transition">
                                    Detail
                                </a>

                                @if ($pengajuan->status === 'menunggu')
                                    <form method="POST"
                                          action="{{ route('warga.pengajuan.cancel', $pengajuan) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?');">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-100 transition">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">
                            Belum ada pengajuan yang dibuat.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection