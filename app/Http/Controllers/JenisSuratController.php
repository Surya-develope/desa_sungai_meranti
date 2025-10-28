<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisSuratController extends Controller
{
    // Fungsi untuk menyimpan jenis surat
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_surat' => 'required|string|max:150',
            'file_template' => 'nullable|file|mimes:pdf,docx,doc|max:10240', // Maksimal 10MB, format file yang diizinkan
        ]);

        // Jika validasi gagal, kembalikan dengan pesan error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
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
            'message' => 'Jenis Surat berhasil disimpan!',
            'data' => $jenisSurat
        ], 201);
    }
}
