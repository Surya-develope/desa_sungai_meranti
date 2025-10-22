<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatus extends Model
{
    protected $fillable = ['pengajuan_id','status','updated_by','catatan'];

    public function pengajuan() { return $this->belongsTo(PengajuanSurat::class); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
}
