<?php
namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use App\Models\PengajuanSurat;    

class SuratGeneratorService
{
    public function generateFromTemplate(PengajuanSurat $pengajuan)
    {
        $jenis = $pengajuan->jenis;
        $templateFile = $jenis->file_template; // e.g. 'sk_tanggungan.docx'
        $templatePath = storage_path("app/templates/{$templateFile}");

        if (!file_exists($templatePath)) {
            throw new \Exception("Template {$templateFile} tidak ditemukan di storage/app/templates");
        }

        // Pastikan direktori temp ada
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) mkdir($tempDir, 0755, true);

        // copy template ke temp working file
        $tempDocPath = storage_path('app/temp/' . uniqid('doc_') . '.docx');
        $htmlTempPath = storage_path('app/temp/' . uniqid('html_') . '.html');
        copy($templatePath, $tempDocPath);

        $tpl = new TemplateProcessor($tempDocPath);

        // isi placeholder dari $pengajuan->data_isian
        $data = $pengajuan->data_isian ?? [];

        foreach ($data as $key => $val) {
            // kalau val array => join
            if (is_array($val)) {
                $tpl->setValue($key, implode(', ', $val));
            } else {
                $tpl->setValue($key, $val);
            }
        }

        // Contoh: set tanggal sekarang dan nama desa
        $tpl->setValue('tanggal', now()->format('d F Y'));
        $tpl->setValue('desa', 'Desa Sungai Meranti');

        try {
            // simpan dokumen docx hasil generate
            $outDocName = 'surat_' . $pengajuan->id . '_' . time() . '.docx';
            $outDocFullPath = storage_path('app/public/surat/' . $outDocName);

            $suratDir = storage_path('app/public/surat');
            if (!is_dir($suratDir)) mkdir($suratDir, 0755, true);

            $tpl->saveAs($outDocFullPath);

            // Konversi ke PDF
            $phpWord = IOFactory::load($outDocFullPath);
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
            $htmlWriter->save($htmlTempPath);
            $htmlContent = file_get_contents($htmlTempPath);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfName = 'surat_' . $pengajuan->id . '_' . time() . '.pdf';
            $pdfPath = storage_path('app/public/surat/' . $pdfName);
            file_put_contents($pdfPath, $dompdf->output());

            // simpan path relative ke storage
            $storagePath = "surat/{$pdfName}";
            $url = Storage::url($storagePath); // butuh php artisan storage:link

            return [
                'path' => $storagePath,
                'url' => $url,
                'docx' => "surat/{$outDocName}",
                'pdf' => $storagePath
            ];

        } finally {
            // Selalu bersihkan file sementara
            if (file_exists($tempDocPath)) {
                @unlink($tempDocPath);
            }
            if (file_exists($htmlTempPath)) {
                @unlink($htmlTempPath);
            }
        }
    }
}
