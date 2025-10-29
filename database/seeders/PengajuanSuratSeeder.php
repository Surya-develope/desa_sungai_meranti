<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengajuanSurat;
use App\Models\UserDesa;
use App\Models\JenisSurat;
use Carbon\Carbon;

class PengajuanSuratSeeder extends Seeder
{
    public function run(): void
    {
        // Get users and jenis surat for relations
        $users = UserDesa::whereHas('role', function($query) {
            $query->where('nama_role', 'warga');
        })->get();
        $jenisSurat = JenisSurat::all();

        if ($users->isEmpty() || $jenisSurat->isEmpty()) {
            $this->command->error('Users or JenisSurat not found. Please run other seeders first.');
            return;
        }

        $pengajuanData = [
            [
                'nik_pemohon' => '6543210987654321', // Siti Warga
                'jenis_surat_id' => $jenisSurat->where('nama_surat', 'Surat Keterangan Domisili')->first()->id ?? 1,
                'tanggal_pengajuan' => Carbon::now()->subDays(5),
                'status' => 'disetujui',
                'data_isian' => [
                    'data_pemohon' => [
                        'nama' => 'Siti Warga',
                        'nik_pemohon' => '6543210987654321',
                        'alamat' => 'RT 001 RW 001, Desa Sungai Meranti',
                        'tempat_lahir' => 'Bengkalis',
                        'tanggal_lahir' => '1990-05-15',
                        'no_hp' => '081234567890',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                    ],
                    'keterangan' => 'Butuh untuk kebutuhan administrasi sekolah anak'
                ],
                'file_syarat' => [
                    [
                        'name' => 'KTP_Siti.pdf',
                        'path' => 'persyaratan/1/KTP_Siti.pdf',
                        'mime' => 'application/pdf',
                        'size_kb' => 256.5
                    ],
                    [
                        'name' => 'KK_Siti.pdf',
                        'path' => 'persyaratan/1/KK_Siti.pdf',
                        'mime' => 'application/pdf',
                        'size_kb' => 189.2
                    ]
                ]
            ],
            [
                'nik_pemohon' => '1111222233334444', // Ahmad Sukma
                'jenis_surat_id' => $jenisSurat->where('nama_surat', 'Surat Keterangan Usaha')->first()->id ?? 2,
                'tanggal_pengajuan' => Carbon::now()->subDays(3),
                'status' => 'menunggu',
                'data_isian' => [
                    'data_pemohon' => [
                        'nama' => 'Ahmad Sukma',
                        'nik_pemohon' => '1111222233334444',
                        'alamat' => 'RT 002 RW 001, Desa Sungai Meranti',
                        'tempat_lahir' => 'Pekanbaru',
                        'tanggal_lahir' => '1985-08-22',
                        'no_hp' => '085123456789',
                        'pekerjaan' => 'Pedagang',
                    ],
                    'keterangan' => 'Untuk modal usaha di bank'
                ],
                'file_syarat' => [
                    [
                        'name' => 'KTP_Ahmad.pdf',
                        'path' => 'persyaratan/2/KTP_Ahmad.pdf',
                        'mime' => 'application/pdf',
                        'size_kb' => 256.5
                    ]
                ]
            ],
            [
                'nik_pemohon' => '5555666677778888', // Rina Melati
                'jenis_surat_id' => $jenisSurat->where('nama_surat', 'Surat Keterangan Tidak Mampu')->first()->id ?? 3,
                'tanggal_pengajuan' => Carbon::now()->subDays(2),
                'status' => 'menunggu_verifikasi',
                'data_isian' => [
                    'data_pemohon' => [
                        'nama' => 'Rina Melati',
                        'nik_pemohon' => '5555666677778888',
                        'alamat' => 'RT 003 RW 002, Desa Sungai Meranti',
                        'tempat_lahir' => 'Dumai',
                        'tanggal_lahir' => '1992-12-10',
                        'no_hp' => '087812345678',
                        'pekerjaan' => 'Tidak Bekerja',
                    ],
                    'keterangan' => 'Untuk bantuan program PKH'
                ],
                'file_syarat' => []
            ],
            [
                'nik_pemohon' => '9999000011112222', // Pak Joko
                'jenis_surat_id' => $jenisSurat->where('nama_surat', 'Surat Pengantar SKCK')->first()->id ?? 4,
                'tanggal_pengajuan' => Carbon::now()->subDays(7),
                'status' => 'ditolak',
                'data_isian' => [
                    'data_pemohon' => [
                        'nama' => 'Pak Joko',
                        'nik_pemohon' => '9999000011112222',
                        'alamat' => 'RT 001 RW 003, Desa Sungai Meranti',
                        'tempat_lahir' => 'Bengkalis',
                        'tanggal_lahir' => '1978-03-18',
                        'no_hp' => '081987654321',
                        'pekerjaan' => 'Karyawan Swasta',
                    ],
                    'keterangan' => 'Untuk keperluan melamar kerja'
                ],
                'file_syarat' => [
                    [
                        'name' => 'KTP_Joko.pdf',
                        'path' => 'persyaratan/4/KTP_Joko.pdf',
                        'mime' => 'application/pdf',
                        'size_kb' => 256.5
                    ]
                ],
                'alasan_penolakan' => 'Dokumen persyaratan tidak lengkap'
            ],
            [
                'nik_pemohon' => '6543210987654321', // Siti Warga
                'jenis_surat_id' => $jenisSurat->where('nama_surat', 'Surat Keterangan Penghasilan')->first()->id ?? 7,
                'tanggal_pengajuan' => Carbon::now()->subDays(1),
                'status' => 'disetujui',
                'data_isian' => [
                    'data_pemohon' => [
                        'nama' => 'Siti Warga',
                        'nik_pemohon' => '6543210987654321',
                        'alamat' => 'RT 001 RW 001, Desa Sungai Meranti',
                        'tempat_lahir' => 'Bengkalis',
                        'tanggal_lahir' => '1990-05-15',
                        'no_hp' => '081234567890',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                        'penghasilan_bulanan' => '1500000',
                    ],
                    'keterangan' => 'Untuk keperluan kredit rumah'
                ],
                'file_syarat' => [
                    [
                        'name' => 'Slip_Gaji_Siti.pdf',
                        'path' => 'persyaratan/7/Slip_Gaji_Siti.pdf',
                        'mime' => 'application/pdf',
                        'size_kb' => 178.3
                    ]
                ]
            ],
            [
                'nik_pemohon' => '1111222233334444', // Ahmad Sukma
                'jenis_surat_id' => $jenisSurat->where('nama_surat', 'Surat Keterangan Kelahiran')->first()->id ?? 5,
                'tanggal_pengajuan' => Carbon::now()->subDays(4),
                'status' => 'menunggu',
                'data_isian' => [
                    'data_pemohon' => [
                        'nama' => 'Ahmad Sukma',
                        'nik_pemohon' => '1111222233334444',
                        'alamat' => 'RT 002 RW 001, Desa Sungai Meranti',
                        'tempat_lahir' => 'Pekanbaru',
                        'tanggal_lahir' => '1985-08-22',
                        'no_hp' => '085123456789',
                        'pekerjaan' => 'Pedagang',
                        'nama_bayi' => 'Muhammad Fajar Sukma',
                        'tanggal_lahir_bayi' => '2025-10-25',
                        'jenis_kelamin' => 'Laki-laki',
                        'tempat_lahir_bayi' => 'RS Umum Sungai Meranti',
                    ],
                    'keterangan' => 'Untuk keperluan pendaftaran akta kelahiran'
                ],
                'file_syarat' => [
                    [
                        'name' => 'Surat_Kelahiran_RS.pdf',
                        'path' => 'persyaratan/5/Surat_Kelahiran_RS.pdf',
                        'mime' => 'application/pdf',
                        'size_kb' => 345.7
                    ]
                ]
            ]
        ];

        foreach ($pengajuanData as $data) {
            PengajuanSurat::updateOrCreate(
                [
                    'nik_pemohon' => $data['nik_pemohon'],
                    'jenis_surat_id' => $data['jenis_surat_id'],
                    'tanggal_pengajuan' => $data['tanggal_pengajuan'],
                ],
                $data
            );
        }

        $this->command->info(count($pengajuanData) . ' sample pengajuan surat created successfully!');
    }
}