<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function jenisSuratList()
    {
        return response()->json(JenisSurat::all());
    }

    public function store(Request $request)
    {
        // validasi dasar
        $request->validate([
            'nik_pemohon' => 'required|string',
            'jenis_surat_id' => 'required|integer|exists:jenis_surat,id',
            'data_isian' => 'required|array', // isian form
            'file_syarat.*' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
        ]);

        // simpan file syarat (bisa banyak)
        $files = [];
        if ($request->hasFile('file_syarat')) {
            foreach ($request->file('file_syarat') as $f) {
                $path = $f->store('public/persyaratan');
                $files[] = $path;
            }
        }

        $pengajuan = PengajuanSurat::create([
            'nik_pemohon' => $request->nik_pemohon,
            'jenis_surat_id' => $request->jenis_surat_id,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'menunggu_verifikasi',
            'data_isian' => $request->data_isian,
            'file_syarat' => $files,
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim',
            'data' => $pengajuan
        ], 201);
    }

    public function show($id)
    {
        $p = PengajuanSurat::with('jenis','suratTerbit','pemohon')->findOrFail($id);
        return response()->json($p);
    }

    public function create()
    {
        $jenisSuratList = JenisSurat::all();
        return view('layout.create', compact('jenisSuratList'));
    }
}
