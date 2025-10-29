@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Informasi lengkap pengajuan Anda</p>
                <h1 class="text-2xl font-bold text-gray-800">Detail Pengajuan Surat</h1>
            </div>
            <a href="{{ route('warga.dashboard') }}"
               class="inline-flex items-center rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                Kembali ke Dashboard
            </a>
        </div>

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

            $dataPemohon = $pengajuan->data_isian['data_pemohon'] ?? [];
            $keterangan = $pengajuan->data_isian['keterangan'] ?? null;
            $lampiran = $pengajuan->file_syarat ?? [];
        @endphp

        <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Nomor Pengajuan</p>
                        <p class="text-lg font-semibold text-gray-800">#{{ $pengajuan->id }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $tanggalPengajuan }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Jenis Surat</p>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $pengajuan->jenis->nama_surat ?? '-' }}
                        </p>
                    </div>
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Data Pemohon</h2>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-y-3 md:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-800">{{ $dataPemohon['nama'] ?? $user->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">NIK</dt>
                        <dd class="mt-1 text-sm text-gray-800">{{ $dataPemohon['nik_pemohon'] ?? $user->nik ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-gray-800">{{ $dataPemohon['alamat'] ?? $user->alamat ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor HP</dt>
                        <dd class="mt-1 text-sm text-gray-800">{{ $user->no_hp ?? '-' }}</dd>
                    </div>
                </dl>

            </div>
        </div>

        <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Keterangan Pengajuan</h2>
            </div>
            <div class="px-6 py-4 text-sm text-gray-700 leading-relaxed">
                {{ $keterangan ?? 'Tidak ada keterangan tambahan.' }}
            </div>
        </div>

        <div class="mb-6 rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Lampiran Persyaratan</h2>
            </div>
            <div class="px-6 py-4">
                @if(count($lampiran) > 0)
                    <ul class="space-y-3">
                        @foreach($lampiran as $index => $file)
                            @php
                                $fileUrl = isset($file['path'])
                                    ? \Illuminate\Support\Facades\Storage::url(str_replace('public/', '', $file['path']))
                                    : '#';
                                $fileName = $file['name'] ?? ('Lampiran-' . ($index + 1));
                                $fileSize = $file['size_kb'] ?? null;
                            @endphp
                            <li class="flex items-center justify-between rounded-md border border-gray-100 bg-gray-50 px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $fileName }}</p>
                                    @if($fileSize)
                                        <p class="text-xs text-gray-500">{{ number_format($fileSize, 2) }} KB</p>
                                    @endif
                                </div>
                                <a href="{{ $fileUrl }}"
                                   target="_blank"
                                   class="inline-flex items-center rounded-md border border-green-200 bg-white px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-50 transition">
                                    Lihat Lampiran
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">Tidak ada lampiran yang diunggah.</p>
                @endif
            </div>
        </div>

        @if($pengajuan->status === 'disetujui' && $pengajuan->suratTerbit && $pengajuan->suratTerbit->file_surat)
            @php
                $suratUrl = \Illuminate\Support\Facades\Storage::url(str_replace('public/', '', $pengajuan->suratTerbit->file_surat));
            @endphp
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-6 py-4 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-emerald-800">Surat Terbit Tersedia</p>
                        <p class="text-sm text-emerald-700">Unduh surat resmi yang telah diterbitkan oleh admin.</p>
                    </div>
                    <a href="{{ $suratUrl }}"
                       download
                       class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        Unduh Surat
                    </a>
                </div>
            </div>
        @elseif($pengajuan->status === 'ditolak' && $pengajuan->alasan_penolakan)
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-6 py-4 shadow-sm">
                <p class="text-sm font-semibold text-rose-800">Pengajuan Ditolak</p>
                <p class="mt-2 text-sm text-rose-700">
                    <span class="font-medium">Alasan penolakan:</span> {{ $pengajuan->alasan_penolakan }}
                </p>
            </div>
        @endif
    </div>
</div>
@endsection