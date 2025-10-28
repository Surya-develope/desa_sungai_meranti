<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;

class JenisSuratController extends Controller
{

     public function jenisSuratList()
    {
        try {
            $jenisSurat = JenisSurat::all();
            
            return response()->json([
                'success' => true,
                'message' => 'Data jenis surat berhasil dimuat',
                'data' => $jenisSurat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function AddLetter(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama_surat' => 'required|string|max:150',
                'file_template' => 'nullable|file|mimes:pdf,docx,doc,xlsx|max:10240', // Maksimal 10MB, format file yang diizinkan
            ]);

            // Jika validasi gagal, kembalikan dengan pesan error
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Proses upload file_template jika ada
            $filePath = null;
            if ($request->hasFile('file_template')) {
                $file = $request->file('file_template');
                $filePath = $file->store('templates', 'public'); // Menyimpan file di folder 'storage/app/public/templates'
            }

            // Simpan data jenis surat ke database
            $jenisSurat = JenisSurat::create([
                'nama_surat' => $request->input('nama_surat'),
                'file_template' => $filePath,
            ]);

            // Kembalikan respon berhasil
            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil disimpan!',
                'data' => $jenisSurat
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getPlaceholders(Request $request, $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);
        $templateFile = storage_path("app/templates/{$jenisSurat->file_template}");

        $ext = strtolower(pathinfo($templateFile, PATHINFO_EXTENSION));
        $placeholders = [];

        if ($ext === 'docx') {
            $placeholders = $this->extractDocxPlaceholders($templateFile);
        } elseif ($ext === 'xlsx') {
            $placeholders = $this->extractXlsxPlaceholders($templateFile);
        }

        return response()->json($placeholders);
    }

    private function extractDocxPlaceholders($filePath)
    {
        $placeholders = [];
        if (!file_exists($filePath)) {
            return $placeholders;
        }

        $phpWord = IOFactory::load($filePath);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . ' ';
                }
            }
        }

        preg_match_all('/\$\{([a-zA-Z0-9_]+)\}/', $text, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key) {
                $placeholders[] = ['key' => $key, 'label' => ucwords(str_replace('_', ' ', $key))];
            }
        }

        return $placeholders;
    }

    private function extractXlsxPlaceholders($filePath)
    {
        $placeholders = [];
        if (!file_exists($filePath)) {
            return $placeholders;
        }

        $spreadsheet = SpreadsheetIOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $cells = $sheet->getCellCollection();

        $text = '';
        foreach ($cells as $cell) {
            $value = $sheet->getCell($cell)->getValue();
            if (is_string($value)) {
                $text .= $value . ' ';
            }
        }

        preg_match_all('/\$\{([a-zA-Z0-9_]+)\}/', $text, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key) {
                $placeholders[] = ['key' => $key, 'label' => ucwords(str_replace('_', ' ', $key))];
            }
        }

        return $placeholders;
    }
}
