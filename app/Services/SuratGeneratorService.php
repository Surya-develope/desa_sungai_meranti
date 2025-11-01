<?php
namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\PengajuanSurat;

class SuratGeneratorService
{

    public function generateFromTemplate(PengajuanSurat $pengajuan)
    {
        $jenis = $pengajuan->jenis;
        $templateFile = $jenis->file_template; // e.g. 'sk_tanggungan.docx' atau '.xlsx'
        $templatePath = storage_path("app/templates/{$templateFile}");
    
        $ext = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));
    
        if ($ext === 'xlsx') {
            return $this->generateFromExcel($pengajuan, $templatePath);
        }

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
        $data = $this->buildTemplateData($pengajuan);

        foreach ($data as $key => $val) {
            // kalau val array => join
            if (is_array($val)) {
                $tpl->setValue($key, implode(', ', $val));
            } else {
                $tpl->setValue($key, $val);
            }
        }

        // Contoh: set data default lainnya
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
    
    private function buildTemplateData(PengajuanSurat $pengajuan): array
    {
        $data = $pengajuan->data_isian ?? [];

        $now = Carbon::now('Asia/Jakarta');
        $now->locale('id');

        $localizedDate = $now->translatedFormat('d F Y');

        return array_merge($data, [
            'year' => $now->format('Y'),
            'Date_Terbit' => $localizedDate,
            'tanggal' => $localizedDate,
        ]);
    }

    private function generateFromExcel(PengajuanSurat $pengajuan, string $templatePath)
    {
        if (!file_exists($templatePath)) {
            throw new \Exception("Template Excel {$templatePath} tidak ditemukan.");
        }
    
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $this->buildTemplateData($pengajuan);
    
        foreach ($data as $key => $val) {
            $placeholder = '{{' . $key . '}}';
            foreach ($sheet->getCellCollection() as $cell) {
                if ($sheet->getCell($cell)->getValue() === $placeholder) {
                    $sheet->setCellValue($cell, is_array($val) ? implode(', ', $val) : $val);
                }
            }
        }
    
        $outExcelName = 'surat_' . $pengajuan->id . '_' . time() . '.xlsx';
        $excelPath = storage_path('app/public/surat/' . $outExcelName);
        $suratDir = storage_path('app/public/surat');
        if (!is_dir($suratDir)) mkdir($suratDir, 0755, true);
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($excelPath);
    
        $storagePath = "surat/{$outExcelName}";
        $url = \Illuminate\Support\Facades\Storage::url($storagePath);
    
        return [
            'path' => $storagePath,
            'url' => $url,
            'excel' => $storagePath
        ];
    }
}
