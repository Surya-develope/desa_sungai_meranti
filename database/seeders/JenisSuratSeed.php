<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeed extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_surat' => 'Surat Keterangan Domisili',
                'file_template' => 'templates/surat_domisili.docx',
                'deskripsi' => 'Surat yang menerangkan domisili penduduk di wilayah desa.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Usaha',
                'file_template' => 'templates/surat_usaha.docx',
                'deskripsi' => 'Surat yang menyatakan bahwa seseorang memiliki usaha di desa.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Tidak Mampu',
                'file_template' => 'templates/surat_tidak_mampu.docx',
                'deskripsi' => 'Surat yang diberikan kepada warga kurang mampu untuk keperluan bantuan sosial.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Pengantar SKCK',
                'file_template' => 'templates/surat_skck.docx',
                'deskripsi' => 'Surat pengantar untuk pengurusan SKCK di kepolisian.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Kelahiran',
                'file_template' => 'templates/surat_kelahiran.docx',
                'deskripsi' => 'Surat yang menerangkan kelahiran bayi di wilayah desa.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Catatan Kriminal',
                'file_template' => 'templates/surat_skc.docx',
                'deskripsi' => 'Surat keterangan tentang catatan kriminal seseorang.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Penghasilan',
                'file_template' => 'templates/surat_penghasilan.docx',
                'deskripsi' => 'Surat yang menerangkan penghasilan seseorang atau keluarga.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Belum Menikah',
                'file_template' => 'templates/surat_belum_menikah.docx',
                'deskripsi' => 'Surat yang menerangkan seseorang belum menikah.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Sudah Menikah',
                'file_template' => 'templates/surat_sudah_menikah.docx',
                'deskripsi' => 'Surat yang menerangkan seseorang sudah menikah.',
                'is_active' => true,
            ],
            [
                'nama_surat' => 'Surat Keterangan Kematian',
                'file_template' => 'templates/surat_kematian.docx',
                'deskripsi' => 'Surat yang menerangkan kematian seseorang di wilayah desa.',
                'is_active' => true,
            ]
        ];

        foreach ($data as $item) {
            JenisSurat::updateOrCreate(
                ['nama_surat' => $item['nama_surat']],
                $item
            );
        }
    }
}
