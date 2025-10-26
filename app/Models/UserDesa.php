<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDesa extends Model
{
    protected $table = 'user_desa';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nik','nama','email','password','role_id','alamat','no_hp'];

    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanSurat::class, 'nik_pemohon', 'nik');
    }
}
