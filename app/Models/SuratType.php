<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratType extends Model
{
    protected $fillable = ['nama_surat', 'kode_surat', 'deskripsi', 'persyaratan', 'template_path'];
    protected $casts = ['persyaratan' => 'array'];

    public function pengajuans()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
