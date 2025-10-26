<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; // ✅ import trait dari Sanctum
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ ganti base class
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserDesa extends Model
{

    use HasApiTokens, Notifiable; 

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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
