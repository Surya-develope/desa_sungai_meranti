<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $fillable = ['pengajuan_id','file_name','file_path','file_type','is_validated'];
    public function pengajuan() { return $this->belongsTo(PengajuanSurat::class); }
}
