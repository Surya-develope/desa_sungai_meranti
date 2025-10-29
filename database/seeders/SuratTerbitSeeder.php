<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratTerbit;
use App\Models\PengajuanSurat;
use App\Models\UserDesa;
use Carbon\Carbon;

class SuratTerbitSeeder extends Seeder
{
    public function run(): void
    {
        // Get approved pengajuan that don't have surat terbit yet
        $approvedPengajuan = PengajuanSurat::where('status', 'disetujui')
            ->whereDoesntHave('suratTerbit')
            ->get();

        if ($approvedPengajuan->isEmpty()) {
            $this->command->info('No approved pengajuan found for surat terbit generation.');
            return;
        }

        $adminUser = UserDesa::whereHas('role', function($query) {
            $query->where('nama_role', 'admin');
        })->first();

        if (!$adminUser) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $suratData = [];

        foreach ($approvedPengajuan as $pengajuan) {
            $pengajuan->load('jenis');
            
            $nomorSurat = $this->generateSuratNumber($pengajuan);
            
            $suratData[] = [
                'pengajuan_id' => $pengajuan->id,
                'tanggal_terbit' => Carbon::now(),
                'file_surat' => "surat_terbit/{$nomorSurat}.pdf",
                'status_cetak' => 'terbit',
            ];
        }

        foreach ($suratData as $data) {
            SuratTerbit::create($data);
        }

        $this->command->info(count($suratData) . ' surat terbit created successfully!');
    }

    private function generateSuratNumber($pengajuan)
    {
        $jenisCode = $this->getJenisCode($pengajuan->jenis->nama_surat);
        $year = date('Y');
        $month = date('m');
        
        // Get existing count for this month
        $existingCount = SuratTerbit::whereYear('tanggal_terbit', $year)
            ->whereMonth('tanggal_terbit', $month)
            ->count();
        
        $sequence = str_pad($existingCount + 1, 4, '0', STR_PAD_LEFT);
        
        return "{$jenisCode}/{$sequence}/DESA-SM/{$month}/{$year}";
    }

    private function getJenisCode($namaSurat)
    {
        $codes = [
            'Surat Keterangan Domisili' => 'SKD',
            'Surat Keterangan Usaha' => 'SKU',
            'Surat Keterangan Tidak Mampu' => 'SKTM',
            'Surat Pengantar SKCK' => 'SPSK',
            'Surat Keterangan Kelahiran' => 'SKK',
            'Surat Keterangan Catatan Kriminal' => 'SKCK2',
            'Surat Keterangan Penghasilan' => 'SKP',
            'Surat Keterangan Belum Menikah' => 'SKBM',
            'Surat Keterangan Sudah Menikah' => 'SKSM',
            'Surat Keterangan Kematian' => 'SKM',
        ];

        return $codes[$namaSurat] ?? 'SK';
    }

    private function generateSuratContent($pengajuan)
    {
        $data = $pengajuan->data_isian['data_pemohon'] ?? [];
        $jenis = $pengajuan->jenis;
        
        $content = "KEPUTUSAN KEPALA DESA SUNGAI MERANTI\n";
        $content .= "NOMOR: " . $this->getCurrentSuratNumber($pengajuan) . "\n\n";
        $content .= "TENTANG\n";
        $content .= strtoupper($jenis->nama_surat) . "\n\n";
        $content .= "KEPALA DESA SUNGAI MERANTI\n\n";
        $content .= "Menimbang : \n";
        $content .= "a. Bahwa untuk keperluan administrasi kependudukan dan pembangunan desa;\n";
        $content .= "b. Bahwa berdasarkan permohonan yang diajukan;\n\n";
        $content .= "Mengingat :\n";
        $content .= "1. Undang-Undang Nomor 6 Tahun 2014 tentang Desa;\n";
        $content .= "2. Peraturan Desa tentang Tata Kelola Pemerintahan Desa;\n\n";
        $content .= "MEMUTUSKAN:\n\n";
        $content .= "Menetapkan :\n";
        $content .= "KEPUTUSAN KEPALA DESA SUNGAI MERANTI TENTANG {$jenis->nama_surat}\n\n";
        $content .= "Pasal 1\n";
        $content .= "memberikan {$jenis->nama_surat} kepada:\n";
        $content .= "Nama\t\t: " . ($data['nama'] ?? 'N/A') . "\n";
        $content .= "NIK\t\t: " . ($data['nik_pemohon'] ?? 'N/A') . "\n";
        $content .= "Alamat\t: " . ($data['alamat'] ?? 'N/A') . "\n";
        $content .= "Tempat/Tgl Lahir\t: " . ($data['tempat_lahir'] ?? 'N/A') . ", " . ($data['tanggal_lahir'] ?? 'N/A') . "\n";
        $content .= "Pekerjaan\t: " . ($data['pekerjaan'] ?? 'N/A') . "\n\n";
        $content .= "Pasal 2\n";
        $content .= "Surat keterangan ini berlaku untuk keperluan administrasi dan tidak berlaku untuk keperluan sorgere.\n\n";
        $content .= "Pasal 3\n";
        $content .= "Surat keterangan ini berlaku selama 3 (tiga) bulan sejak tanggal diterbitkan.\n\n";
        $content .= "Ditetapkan di Sungai Meranti\n";
        $content .= "Pada tanggal " . date('d F Y') . "\n\n";
        $content .= "KEPALA DESA SUNGAI MERANTI\n\n\n\n";
        $content .= "_________________________\n";
        $content .= "Budi Admin\n";
        $content .= "NIP. 197001011990031001";
        
        return $content;
    }

    private function getCurrentSuratNumber($pengajuan)
    {
        $jenisCode = $this->getJenisCode($pengajuan->jenis->nama_surat);
        $year = date('Y');
        $month = date('m');
        $sequence = sprintf('%04d', $pengajuan->id);
        
        return "{$jenisCode}/{$sequence}/DESA-SM/{$month}/{$year}";
    }
}