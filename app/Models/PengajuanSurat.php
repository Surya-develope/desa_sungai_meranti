<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';
    protected $fillable = [
        'nik_pemohon','jenis_surat_id','tanggal_pengajuan','status',
        'data_isian','file_syarat','alasan_penolakan'
    ];

    protected $casts = [
        'data_isian' => 'array',
        'file_syarat' => 'array',
        'tanggal_pengajuan' => 'date',
    ];

    public function pemohon()
    {
        return $this->belongsTo(UserDesa::class, 'nik_pemohon', 'nik');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function suratTerbit()
    {
        return $this->hasOne(SuratTerbit::class, 'pengajuan_id');
    }
}
