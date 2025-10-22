<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat'; // ✅ tambahkan ini!

    protected $fillable = [
        'user_id',
        'surat_type_id',
        'nomor_surat',
        'tanggal_pengajuan',
        'status',
        'keterangan',
        'tracking_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suratType()
    {
        return $this->belongsTo(SuratType::class, 'surat_type_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatStatus::class, 'pengajuan_id');
    }
}
