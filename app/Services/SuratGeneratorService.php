<?php
namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use App\Models\PengajuanSurat;    

class DocumentGenerator
{
    public function generateFromTemplate(PengajuanSurat $pengajuan)
    {
        $jenis = $pengajuan->jenis;
        $templateFile = $jenis->file_template; // e.g. 'sk_tanggungan.docx'
        $templatePath = storage_path("app/templates/{$templateFile}");

        if (!file_exists($templatePath)) {
            throw new \Exception("Template {$templateFile} tidak ditemukan di storage/app/templates");
        }

        // copy template ke temp working file
        $tempDocPath = storage_path('app/temp/' . uniqid('doc_') . '.docx');
        if (!is_dir(storage_path('app/temp'))) mkdir(storage_path('app/temp'), 0755, true);
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

        // simpan dokumen docx hasil generate
        $outDocName = 'surat_' . $pengajuan->id . '_' . time() . '.docx';
        $outDocStorage = "public/surat/{$outDocName}";
        $outDocFullPath = storage_path('app/public/surat/' . $outDocName);

        if (!is_dir(storage_path('app/public/surat'))) mkdir(storage_path('app/public/surat'), 0755, true);

        $tpl->saveAs($outDocFullPath);

        // Convert DOCX to HTML then to PDF using Dompdf is one approach.
        // Simpler: PhpWord can save to PDF if you have TCPDF or DomPDF writer, 
        // but for portability, we convert docx -> HTML -> dompdf -> PDF.
        $phpWord = IOFactory::load($outDocFullPath);
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

        $htmlTemp = storage_path('app/temp/' . uniqid('html_') . '.html');
        $htmlWriter->save($htmlTemp);
        $htmlContent = file_get_contents($htmlTemp);

        // instantiate dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfName = 'surat_' . $pengajuan->id . '_' . time() . '.pdf';
        $pdfPath = storage_path('app/public/surat/' . $pdfName);
        file_put_contents($pdfPath, $dompdf->output());

        // bersihkan temp files jika perlu
        @unlink($tempDocPath);
        @unlink($htmlTemp);

        // simpan path relative ke storage
        $storagePath = "surat/{$pdfName}";
        $url = Storage::url($storagePath); // butuh php artisan storage:link

        return [
            'path' => $storagePath,
            'url' => $url,
            'docx' => "surat/{$outDocName}",
            'pdf' => $storagePath
        ];
    }
}
