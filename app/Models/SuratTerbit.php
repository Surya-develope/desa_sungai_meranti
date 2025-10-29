<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SuratTerbit extends Model
{
    protected $table = 'surat_terbit';
    protected $fillable = ['pengajuan_id','file_surat','tanggal_terbit','status_cetak'];
    public $timestamps = false;

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }
}
