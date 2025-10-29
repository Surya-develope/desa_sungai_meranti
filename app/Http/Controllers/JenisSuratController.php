<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use PhpOffice\PhpWord\IOFactory;

class JenisSuratController extends Controller
{
    /**
     * Menampilkan semua jenis surat.
     */
    public function jenisSuratList()
    {
        try {
            $jenisSurat = JenisSurat::all();

            return response()->json([
                'success' => true,
                'message' => 'Data jenis surat berhasil dimuat',
                'data' => $jenisSurat,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jenis surat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menambahkan jenis surat baru dan membaca placeholder dari file template.
     */
    public function AddLetter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_surat' => 'required|string|max:150',
                'file_template' => 'nullable|file|mimes:pdf,docx,doc,xlsx|max:10240',
                'deskripsi' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $filePath = null;
            $formStructure = [];

            if ($request->hasFile('file_template')) {
                $file = $request->file('file_template');
                $filePath = $file->store('templates', 'public');
                $ext = strtolower($file->getClientOriginalExtension());
                $absolutePath = Storage::disk('public')->path($filePath);
                $formStructure = $this->extractFormStructure($absolutePath, $ext);
            }

            $jenisSurat = JenisSurat::create([
                'nama_surat' => $request->input('nama_surat'),
                'file_template' => $filePath,
                'form_structure' => $formStructure,
                'deskripsi' => $request->input('deskripsi'),
                'is_active' => $request->boolean('is_active', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil disimpan!',
                'data' => $jenisSurat,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jenis surat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Memperbarui data jenis surat beserta template dan struktur form-nya.
     */
    public function update(Request $request, JenisSurat $jenisSurat)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_surat' => 'sometimes|string|max:150',
                'deskripsi' => 'sometimes|nullable|string',
                'is_active' => 'sometimes|boolean',
                'file_template' => 'nullable|file|mimes:pdf,docx,doc,xlsx|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $payload = $validator->validated();
            $updates = [];

            if (array_key_exists('nama_surat', $payload)) {
                $updates['nama_surat'] = $payload['nama_surat'];
            }

            if (array_key_exists('deskripsi', $payload)) {
                $updates['deskripsi'] = $payload['deskripsi'];
            }

            if (array_key_exists('is_active', $payload)) {
                $updates['is_active'] = $payload['is_active'];
            }

            if ($request->hasFile('file_template')) {
                $file = $request->file('file_template');
                $filePath = $file->store('templates', 'public');
                $ext = strtolower($file->getClientOriginalExtension());
                $absolutePath = Storage::disk('public')->path($filePath);
                $formStructure = $this->extractFormStructure($absolutePath, $ext);

                if ($jenisSurat->file_template) {
                    Storage::disk('public')->delete($jenisSurat->file_template);
                }

                $updates['file_template'] = $filePath;
                $updates['form_structure'] = $formStructure;
            }

            if (!empty($updates)) {
                $jenisSurat->update($updates);
            }

            $jenisSurat->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil diperbarui!',
                'data' => $jenisSurat,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jenis surat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil placeholder dari template berdasarkan ID surat.
     */
    public function getPlaceholders(Request $request, $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        if (!$jenisSurat->file_template || !Storage::disk('public')->exists($jenisSurat->file_template)) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $templateFile = Storage::disk('public')->path($jenisSurat->file_template);
        $ext = strtolower(pathinfo($templateFile, PATHINFO_EXTENSION));

        $placeholders = $this->extractFormStructure($templateFile, $ext);

        return response()->json([
            'success' => true,
            'data' => $placeholders,
        ]);
    }

    /**
     * Ekstrak struktur form berdasarkan tipe file template.
     */
    private function extractFormStructure(string $filePath, string $extension): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        if (in_array($extension, ['doc', 'docx'])) {
            return $this->extractDocxPlaceholders($filePath);
        }

        if ($extension === 'xlsx') {
            return $this->extractXlsxPlaceholders($filePath);
        }

        return [];
    }

    /**
     * Ekstrak placeholder dari file DOCX/DOC.
     */
    private function extractDocxPlaceholders(string $filePath): array
    {
        $placeholders = [];

        if (!file_exists($filePath)) {
            return $placeholders;
        }

        try {
            $phpWord = IOFactory::load($filePath);
        } catch (\Throwable $throwable) {
            return $placeholders;
        }

        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text .= $this->extractTextFromElement($element);
            }
        }

        preg_match_all('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', $text, $matches);

        if (!empty($matches[1])) {
            foreach (array_unique($matches[1]) as $key) {
                $key = trim($key);
                $placeholders[] = [
                    'name' => $key,
                    'label' => ucwords(str_replace('_', ' ', $key)),
                    'type' => 'text',
                ];
            }
        }

        return $placeholders;
    }

    /**
     * Rekursif ambil teks dari semua elemen dalam dokumen Word (termasuk nested runs).
     */
    private function extractTextFromElement($element): string
    {
        $text = '';

        if (method_exists($element, 'getText')) {
            $value = $element->getText();
            if (is_string($value)) {
                $text .= ' ' . $value;
            }
        }

        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $text .= $this->extractTextFromElement($child);
            }
        }

        return $text;
    }

    /**
     * Ekstrak placeholder dari file XLSX.
     */
    private function extractXlsxPlaceholders(string $filePath): array
    {
        $placeholders = [];

        if (!file_exists($filePath)) {
            return $placeholders;
        }

        try {
            $spreadsheet = SpreadsheetIOFactory::load($filePath);
        } catch (\Throwable $throwable) {
            return $placeholders;
        }

        $sheet = $spreadsheet->getActiveSheet();
        $cells = $sheet->getCellCollection();
        $text = '';

        foreach ($cells as $cell) {
            $value = $sheet->getCell($cell)->getValue();
            if (is_string($value)) {
                $text .= $value . ' ';
            }
        }

        preg_match_all('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', $text, $matches);

        if (!empty($matches[1])) {
            foreach (array_unique($matches[1]) as $key) {
                $key = trim($key);
                $placeholders[] = [
                    'name' => $key,
                    'label' => ucwords(str_replace('_', ' ', $key)),
                    'type' => 'text',
                ];
            }
        }

        return $placeholders;
    }
}
