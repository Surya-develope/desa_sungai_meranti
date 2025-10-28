<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JenisSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $jenisSurat = JenisSurat::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Daftar jenis surat berhasil diambil',
                'data' => $jenisSurat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_surat' => 'required|string|max:150|unique:jenis_surat,nama_surat',
                'file_template' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string|max:500',
                'is_active' => 'boolean'
            ]);

            DB::beginTransaction();
            
            $jenisSurat = JenisSurat::create([
                'nama_surat' => $request->nama_surat,
                'file_template' => $request->file_template,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->is_active ?? true
            ]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jenis surat berhasil dibuat',
                'data' => $jenisSurat
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisSurat $jenisSurat)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Detail jenis surat berhasil diambil',
                'data' => $jenisSurat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisSurat $jenisSurat)
    {
        try {
            $request->validate([
                'nama_surat' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('jenis_surat')->ignore($jenisSurat->id)
                ],
                'file_template' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string|max:500',
                'is_active' => 'boolean'
            ]);

            DB::beginTransaction();
            
            $jenisSurat->update([
                'nama_surat' => $request->nama_surat,
                'file_template' => $request->file_template,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->is_active
            ]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jenis surat berhasil diperbarui',
                'data' => $jenisSurat->fresh()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisSurat $jenisSurat)
    {
        try {
            DB::beginTransaction();
            
            // Cek apakah jenis surat digunakan di pengajuan_surat
            $pengajuanCount = \App\Models\PengajuanSurat::where('jenis_surat_id', $jenisSurat->id)->count();
            
            if ($pengajuanCount > 0) {
                // Jika ada pengajuan yang menggunakan jenis surat ini, set is_active = false
                $jenisSurat->update(['is_active' => false]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Jenis surat berhasil dinonaktifkan (tidak dapat dihapus karena masih digunakan)',
                    'data' => $jenisSurat
                ]);
            } else {
                // Jika tidak ada pengajuan, bisa dihapus
                $jenisSurat->delete();
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Jenis surat berhasil dihapus'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status aktif/non-aktif jenis surat
     */
    public function toggleStatus(JenisSurat $jenisSurat)
    {
        try {
            DB::beginTransaction();
            
            $jenisSurat->update(['is_active' => !$jenisSurat->is_active]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status jenis surat berhasil diperbarui',
                'data' => $jenisSurat
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active jenis surat only (for public use)
     */
    public function activeOnly()
    {
        try {
            $jenisSurat = JenisSurat::where('is_active', true)
                ->orderBy('nama_surat', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Daftar jenis surat aktif berhasil diambil',
                'data' => $jenisSurat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}