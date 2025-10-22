<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanDetail extends Model
{
    protected $fillable = ['pengajuan_id','field_name','field_label','field_value'];
    public function pengajuan() { return $this->belongsTo(PengajuanSurat::class); }
}
