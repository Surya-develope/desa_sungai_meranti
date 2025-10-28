<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeeder extends Seeder
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
        ];

        foreach ($data as $item) {
            JenisSurat::updateOrCreate(
                ['nama_surat' => $item['nama_surat']],
                $item
            );
        }
    }
}
