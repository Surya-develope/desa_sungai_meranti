<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;

class SuratGeneratorService
{
    public function generate($pengajuan)
    {
        $template = new TemplateProcessor(storage_path('app/' . $pengajuan->suratType->template_path));

        foreach ($pengajuan->details as $detail) {
            $template->setValue($detail->field_name, $detail->field_value);
        }

        $outputPath = 'surat/' . now()->format('Y/m/') . 'surat_' . $pengajuan->id . '.docx';
        $template->saveAs(storage_path('app/' . $outputPath));

        return $outputPath;
    }
}
