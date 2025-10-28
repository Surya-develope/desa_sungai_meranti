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

    public function store(Request $request)
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
                'file_syarat.*' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
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
                $path = $file->store('public/persyaratan');
                $files[] = $path;
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
            'status' => 'menunggu_verifikasi',
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
        $jenisSuratList = JenisSurat::all();
        return view('layout.create', compact('jenisSuratList'));
    }
}
