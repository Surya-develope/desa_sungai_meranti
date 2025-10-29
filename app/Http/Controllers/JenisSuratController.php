<?php
namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class JenisSuratController extends Controller
{
    // Menampilkan semua jenis surat
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

    // Menambahkan jenis surat baru
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

    // Update jenis surat
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

            if (isset($payload['nama_surat'])) $updates['nama_surat'] = $payload['nama_surat'];
            if (isset($payload['deskripsi'])) $updates['deskripsi'] = $payload['deskripsi'];
            if (isset($payload['is_active'])) $updates['is_active'] = $payload['is_active'];

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

            if (!empty($updates)) $jenisSurat->update($updates);

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

    // Ambil placeholder dari template
    public function getPlaceholders(Request $request, $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        if (!$jenisSurat->file_template || !Storage::disk('public')->exists($jenisSurat->file_template)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $templateFile = Storage::disk('public')->path($jenisSurat->file_template);
        $ext = strtolower(pathinfo($templateFile, PATHINFO_EXTENSION));
        $placeholders = $this->extractFormStructure($templateFile, $ext);

        return response()->json(['success' => true, 'data' => $placeholders]);
    }

    // Extract form structure dari file template
    private function extractFormStructure(string $filePath, string $extension): array
    {
        if (!file_exists($filePath)) return [];
        if (in_array($extension, ['doc', 'docx'])) return $this->extractDocxPlaceholders($filePath);
        if ($extension === 'xlsx') return $this->extractXlsxPlaceholders($filePath);
        return [];
    }

    private function extractDocxPlaceholders(string $filePath): array
    {
        // Simplified version - returns empty array for now
        // In production, you would implement proper docx parsing here
        return [];
    }

    private function extractTextFromElement($element): string
    {
        $text = '';

        if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            foreach ($element->getElements() as $child) {
                $text .= $this->extractTextFromElement($child);
            }
            return $text;
        }

       
        if (method_exists($element, 'getText')) {
            $value = $element->getText();
            if (is_string($value)) {
                $text .= $value . ' ';
            }
        }

        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $text .= $this->extractTextFromElement($child);
            }
        }

        return $text;
    }

    private function extractXlsxPlaceholders(string $filePath): array
    {
        // Simplified version - returns empty array for now
        // In production, you would implement proper xlsx parsing here
        return [];
    }

    // Admin methods for web interface
    public function adminIndex()
    {
        $jenisSuratList = JenisSurat::orderBy('created_at', 'desc')->get();
        return view('admin.jenis-surat.index', compact('jenisSuratList'));
    }

    public function adminStore(Request $request)
    {
        // API call from frontend
        return $this->AddLetter($request);
    }

    public function adminShow(JenisSurat $jenisSurat)
    {
        return response()->json([
            'success' => true,
            'data' => $jenisSurat
        ]);
    }

    public function adminUpdate(Request $request, JenisSurat $jenisSurat)
    {
        // API call from frontend
        return $this->update($request, $jenisSurat);
    }

    public function adminDestroy(JenisSurat $jenisSurat)
    {
        try {
            if ($jenisSurat->file_template) {
                Storage::disk('public')->delete($jenisSurat->file_template);
            }
            $jenisSurat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jenis surat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function adminToggleStatus(JenisSurat $jenisSurat)
    {
        try {
            $jenisSurat->update([
                'is_active' => !$jenisSurat->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status jenis surat berhasil diperbarui!',
                'data' => $jenisSurat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status jenis surat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Additional methods for web interface
    public function activeOnly()
    {
        try {
            $jenisSurat = JenisSurat::where('is_active', true)->get();
            return response()->json([
                'success' => true,
                'message' => 'Data jenis surat aktif berhasil dimuat',
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

    public function index()
    {
        // Public index - shows all types
        return $this->jenisSuratList();
    }

    public function show(JenisSurat $jenisSurat)
    {
        return response()->json([
            'success' => true,
            'data' => $jenisSurat
        ]);
    }

    public function destroy(JenisSurat $jenisSurat)
    {
        return $this->adminDestroy($jenisSurat);
    }
}
