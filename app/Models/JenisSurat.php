<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';
    protected $fillable = [
        'nama_surat',
        'file_template',
        'form_structure',
        'deskripsi',
        'is_active'
    ];
    
    protected $casts = [
        'form_structure' => 'array',
        'is_active' => 'boolean'
    ];

    // Scope untuk jenis surat yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relasi ke pengajuan surat
    public function pengajuanSurat()
    {
        return $this->hasMany(\App\Models\PengajuanSurat::class, 'jenis_surat_id');
    }
}
