<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';
    protected $fillable = ['nama_surat','file_template'];
}
