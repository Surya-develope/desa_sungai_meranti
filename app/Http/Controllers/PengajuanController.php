<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userNik = $request->user()->nik;
            $pengajuanList = PengajuanSurat::with('jenis')
                ->where('nik_pemohon', $userNik)
                ->orderByDesc('tanggal_pengajuan')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar pengajuan berhasil dimuat',
                'data' => $pengajuanList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $pengajuan = PengajuanSurat::findOrFail($id);

            if ($pengajuan->nik_pemohon !== $request->user()->nik) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak membatalkan pengajuan ini'
                ], 403);
            }

            if ($pengajuan->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan hanya bisa dibatalkan jika status menunggu'
                ], 400);
            }

            $pengajuan->status = 'dibatalkan';
            $pengajuan->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dibatalkan',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pengajuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function AddPengajuan(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'jenis_surat_id' => 'required|integer|exists:jenis_surat,id',
                'data_pemohon' => 'required|array',
                'data_pemohon.nama' => 'required|string',
                'data_pemohon.nik_pemohon' => 'required|string',
                'data_pemohon.alamat' => 'required|string',
                'keterangan' => 'required|string',
                'file_syarat.*' => 'sometimes|file|mimes:jpg,jpeg,png,pdf,docx|max:204', // 5MB, mendukung docx & xlsx
            ]);

            $nik = $validated['data_pemohon']['nik_pemohon'];
            $jenisSuratId = $validated['jenis_surat_id'];
            $dataPemohon = $validated['data_pemohon'];
            $keterangan = $validated['keterangan'];

            // Upload files
            $files = $this->uploadFiles($request, $jenisSuratId);

            // Create pengajuan
            $pengajuan = $this->createPengajuan($nik, $jenisSuratId, $dataPemohon, $files, $keterangan);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim',
                'data' => [
                    'id' => $pengajuan->id ?? $pengajuan->nik_pemohon,
                    'nik_pemohon' => $pengajuan->nik_pemohon,
                    'jenis_surat_id' => $pengajuan->jenis_surat_id,
                    'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
                    'status' => $pengajuan->status,
                    'data_isian' => $pengajuan->data_isian,
                    'file_syarat' => $pengajuan->file_syarat
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload and store files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $jenisSuratId
     * @return array
     */
    private function uploadFiles(Request $request, $jenisSuratId)
    {
        $files = [];
        if ($request->hasFile('file_syarat')) {
            foreach ($request->file('file_syarat') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $allowedExt = ['jpg', 'jpeg', 'png', 'pdf', 'docx', 'xlsx'];
                if (!in_array($ext, $allowedExt)) {
                    continue; // skip file tidak valid
                }

                // simpan file ke folder berdasarkan jenis surat
                $folder = 'public/persyaratan/' . $jenisSuratId;
                $filename = Str::uuid() . '.' . $ext;
                $path = $file->storeAs($folder, $filename);

                // catat metadata file
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                    'size_kb' => round($file->getSize() / 1024, 2)
                ];
            }
        }
        return $files;
    }

    /**
     * Create a new pengajuan surat record.
     *
     * @param  string  $nik
     * @param  int  $jenisSuratId
     * @param  array  $dataPemohon
     * @param  array  $files
     * @param  string  $keterangan
     * @return \App\Models\PengajuanSurat
     */
    private function createPengajuan($nik, $jenisSuratId, $dataPemohon, $files, $keterangan)
    {
        return PengajuanSurat::create([
            'nik_pemohon' => $nik,
            'jenis_surat_id' => $jenisSuratId,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'menunggu',
            'data_isian' => [
                'data_pemohon' => $dataPemohon,
                'keterangan' => $keterangan
            ],
            'file_syarat' => $files,
        ]);
    }

    public function show($id)
    {
        try {
            $pengajuan = PengajuanSurat::with('jenis','suratTerbit','pemohon')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan berhasil dimuat',
                'data' => $pengajuan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function create()
    {
        $jenisSuratList = JenisSurat::where('is_active', true)->get();
        return view('layout.create', compact('jenisSuratList'));
    }
}
